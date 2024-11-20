<?php

namespace App\Http\Controllers;


use App\Models\Facility; 
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationStatusUpdate;
use Illuminate\Support\Facades\DB;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FacilitiesExport;
use Illuminate\Support\Str;
use Carbon\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;

class NTPManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Display the NTP Manager Dashboard
    public function dashboard()
    {
      // Count applications with 'passed' or 'verified' statuses
      $totalApplications = Application::whereIn('status', ['passed', 'verified'])->count();

      // Count the unique facilities associated with 'passed' applications
      // Using the 'facility' column from the applications table to count distinct facilities
      $totalFacilities = Application::where('status', 'passed')
                                    ->distinct('facility')  // Count unique facilities based on the 'facility' column
                                    ->count();

      // Pass data to the view
      return view('ntpmanager.dashboard', compact('totalApplications', 'totalFacilities'));
    }

    // Show list of verified applications
    public function applications()
    {
        $applications = Application::whereIn('status', ['verified', 'passed', 'failed'])->get();
    return view('ntpmanager.applications.index', compact('applications'));
    }

    // Show details of a specific application
    public function show(Application $application)
    {
         // Allow access for both 'verified' and 'passed' statuses
    if (!in_array($application->status, ['verified', 'passed', 'failed'])) {
        return redirect()->route('ntpmanager.applications.index')->with('error', 'Application not found or not verified.');
    }
    return view('ntpmanager.applications.show', compact('application'));
    }

    // Set a visit schedule for an application
    public function setSchedule(Request $request, Application $application)
    {
        $request->validate([
            'visit_date' => 'required|date',
        ]);
    
        // Ensure visit date is properly parsed (ensure it's in 'Y-m-d' format)
        $visitDate = Carbon::parse($request->input('visit_date'))->format('Y-m-d');
    
        // Update ntp_visit_date instead of visit_date
        $application->ntp_visit_date = $visitDate;
        $application->save();
    
        return redirect()->route('ntpmanager.applications.show', $application)
                         ->with('success', 'Visit schedule set successfully.');
    }
    public function updateStatus(Request $request, Application $application)
    {
        $request->validate([
            'status' => 'required|string|in:passed,failed',
            'remarks' => 'nullable|string|required_if:status,failed',
        ]);
    
        try {
            if ($request->status === 'passed') {
                $application->update([
                    'status' => 'passed',
                    'registration_no' => $this->generateUniqueRegistrationNumber(),
                    'date_renewal' => now(),
                    'date_expired' => now()->addYears(3),
                ]);
            } elseif ($request->status === 'failed') {
                $application->update([
                    'status' => 'failed',
                    'ntp_remarks' => $request->remarks,
                ]);
            }
    
            // Send dynamic email notification
            $this->sendNotification($application, $request->status === 'passed' 
                ? 'Your application has been approved.'
                : $request->remarks);
    
            return redirect()->route('ntpmanager.applications.index')
                ->with('success', 'Application status updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating status: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update status.']);
        }
    } 
    
    private function generateUniqueRegistrationNumber()
    {
        // Loop until we find a unique registration number
        do {
            $registrationNumber = mt_rand(10000000, 99999999); // Generates an 8-digit number
            $exists = Application::where('registration_no', $registrationNumber)->exists();
        } while ($exists);
    
        return $registrationNumber;
    }
    
    private function sendNotification($application, $message)
    {
        try {
            $user = Auth::user(); // Current NTP manager
            $senderEmail = $user->email;
            $senderName = $user->name;
    
            $subject = "Application Status Update for {$application->facility->name}";
            $head_of_unit = $application->headOfUnit ?? (object)['name' => 'Head of Unit'];
    
            Mail::to($application->email) // Send to the client
                ->send(new ApplicationStatusUpdate(
                    $head_of_unit,
                    $application->facility,
                    $application->status,
                    $message,
                    $senderEmail,
                    $subject
                ));
        } catch (\Exception $e) {
            Log::error('Error sending email: ' . $e->getMessage());
        }
    }
    // Show facilities with a 'passed' status
    public function facilities(Request $request)
{
    // Get the selected province and status from the request
    $selectedProvince = $request->input('province_city', ''); // Default to empty string
    $selectedStatus = $request->input('status', 'all'); // Default to 'all'

    // Get unique provinces
    $provinces = DB::table('applications')
        ->where('status', 'passed')
        ->pluck('province_city')
        ->unique();

    // Query to get facilities based on the selected province and status
    $facilities = DB::table('applications')
        ->select('facility', 'province_city', 'registration_no', 'date_renewal', 'date_expired', 'status')
        ->when($selectedProvince, function ($query, $selectedProvince) {
            return $query->where('province_city', $selectedProvince);
        })
        ->when($selectedStatus == 'passed', function ($query) {
            return $query->where('status', 'passed');
        })
        ->when($selectedStatus == 'expired', function ($query) {
            return $query->where('status', 'passed')
                         ->whereDate('date_renewal', '<', now()->subYears(3));
        })
        ->orderBy('province_city')
        ->get();

    // Return the facilities view
    return view('ntpmanager.facilities', compact('facilities', 'provinces', 'selectedProvince', 'selectedStatus'));
}

// Export facilities data as a PDF file
public function exportPDF(Request $request)
{
    $selectedProvince = $request->get('province_city', '');
    $selectedStatus = $request->get('status', 'all');

    // Log the incoming request for debugging
    \Log::info('Export PDF requested with filters', [
        'province_city' => $selectedProvince,
        'status' => $selectedStatus,
    ]);

    // Build the query to get applications based on filters
    $applications = Application::select('facility', 'province_city', 'registration_no', 'date_renewal', 'date_expired', 'status')
        ->when($selectedProvince, function ($query, $selectedProvince) {
            return $query->where('province_city', $selectedProvince);
        })
        ->when($selectedStatus == 'passed', function ($query) {
            return $query->where('status', 'passed');
        })
        ->when($selectedStatus == 'expired', function ($query) {
            return $query->where('status', 'passed')
                         ->whereDate('date_renewal', '<', now()->subYears(3));
        })
        ->orderBy('province_city')
        ->get();

    // Log the number of applications fetched
    \Log::info('Number of applications fetched for PDF', ['count' => $applications->count()]);

    // Return the PDF view with filtered applications data
    return view('ntpmanager.facilities_pdf', compact('applications', 'selectedStatus', 'selectedProvince'));
}

    // Export facilities data as a Word document
    public function exportWord()
    {
        return Excel::download(new FacilitiesExport, 'facilities.docx');
    }

    // Confirm and update application status to 'failed'
    public function confirmFailedStatus(Request $request, Application $application)
    {
        $application->status = 'failed';
        $application->save();

        $this->sendNotification($application, 'Your application has been marked as failed. Please reapply.');

        return redirect()->route('ntpmanager.applications.show', $application)
                         ->with('success', 'Application status updated to failed successfully.');
    }

   public function generate(Application $application)
    {$templatePath = storage_path('app/certificates/template.docx');
    $tempDocPath = storage_path("app/certificates/temp_certificate_{$application->id}.docx");

    Log::info('Generating certificate for application', ['application_id' => $application->id]);

    try {
        // Load the Word template
        $phpWord = new TemplateProcessor($templatePath);
        Log::info('Template loaded successfully');

        // Set template values
        $phpWord->setValue('facility_name', $application->facility);
        
        // Set passed date (Renewal Date)
        $phpWord->setValue('passed_date', Carbon::parse($application->date_passed)->format('F j, Y')); // e.g., November 7, 2024
        
        // Set Registration No.
        $phpWord->setValue('registration_no', $application->registration_no);

        // Calculate expiry date: 3 years after renewal date (the actual logic)
        if (isset($application->date_renewal)) {
            $expiryDate = Carbon::parse($application->date_renewal)->addYears(3)->format('F j, Y'); // Adding 3 years to renewal date
        } else {
            $expiryDate = 'N/A';  // If no renewal date is provided
        }

        // Set expiry date in the template (Expiry Date)
        $phpWord->setValue('expiry_date', $expiryDate); // This should now correctly be November 7, 2027

        // Regional Director (fixed value)
        $regionalDirector = "ARISTIDES CONCEPCION TAN, MD, MPH, CESO III"; 
        $phpWord->setValue('regional_director', $regionalDirector);

        // Save the document
        $phpWord->saveAs($tempDocPath);
        Log::info('Certificate saved successfully at', ['path' => $tempDocPath]);

        // Return the generated certificate for download
        return response()->download($tempDocPath, "certificate_{$application->facility}.docx")->deleteFileAfterSend(true);
    } catch (\Exception $e) {
        Log::error('Failed to generate certificate', ['error' => $e->getMessage()]);
        return redirect()->back()->with('error', 'Failed to generate the certificate. Please try again.');
    }
    }

    public function previewCertificate(Application $application)
{
    // Ensure the renewal date is in the correct format (MM-DD-YYYY)
    $renewalDate = $application->date_renewal
        ? \Carbon\Carbon::createFromFormat('m-d-Y', $application->date_renewal)->format('F j, Y')
        : 'Not available';

    // Expiry date: add 3 years to the renewal date and format it
    $expiryDate = $application->date_renewal
        ? \Carbon\Carbon::createFromFormat('m-d-Y', $application->date_renewal)->addYears(3)->format('F j, Y')
        : 'Not available';

    $regionalDirector = "ARISTIDES CONCEPCION TAN, MD, MPH, CESO III";

    // Pass the formatted data to the view
    return view('ntpmanager.certificate-preview', compact('renewalDate', 'expiryDate', 'regionalDirector', 'application'));
}

public function confirmDownload(Application $application)
{
    $templatePath = storage_path('app/certificates/template.docx');
    $tempDocPath = storage_path("app/certificates/temp_certificate_{$application->id}.docx");

    try {
        $phpWord = new TemplateProcessor($templatePath);
        
        // Set the facility name in the template
        $phpWord->setValue('facility_name', $application->facility);

        // Ensure date_renewal is parsed and formatted as "November 7, 2024"
        $passedDate = Carbon::createFromFormat('m-d-Y', $application->date_renewal)->format('F j, Y');
        $phpWord->setValue('passed_date', $passedDate);

        // Set Registration No.
        $phpWord->setValue('registration_no', $application->registration_no);

        // Ensure expiry date is correctly calculated (3 years added) and formatted
        $expiryDate = isset($application->date_renewal)
            ? Carbon::createFromFormat('m-d-Y', $application->date_renewal)->addYears(3)->format('F j, Y') // Expiry date with 3 years added
            : 'N/A';

        // Set expiry_date in the template
        $phpWord->setValue('expiry_date', $expiryDate);

        // Save the document to a temporary path
        $phpWord->saveAs($tempDocPath);
        Log::info('Certificate saved successfully at', ['path' => $tempDocPath]);

        // Return the generated certificate for download
        return response()->download($tempDocPath, "certificate_{$application->facility}.docx")->deleteFileAfterSend(true);
    } catch (\Exception $e) {
        Log::error('Failed to generate certificate', ['error' => $e->getMessage()]);
        return response()->json(['error' => 'Failed to generate the certificate. Please try again.'], 500);
    }
}

}