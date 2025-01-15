<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Mail\ApplicationVerified;
use App\Notifications\ApplicationStatusVerified;
use Carbon\Carbon;

class ApplicationController extends Controller
{
    public function indexdashboard(Request $request)
    {
        $query = Application::query();

        
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        
        $applications = $query->simplePaginate(10)->appends($request->except('page'));

        // counts for ongoing, pending, denied, verified
        $ongoingCount = Application::where('status', 'ongoing')->count();
        $pendingCount = Application::where('status', 'pending')->count();
        $deniedCount = Application::where('status', 'denied')->count();
        $passedCount = Application::where('status', 'passed')->count();
        $verifiedCount = Application::where('status', 'verified')->count();
        return view('pdoho.dashboard', compact('applications', 'ongoingCount', 'pendingCount', 'verifiedCount', 'deniedCount','passedCount'));
    }

    public function index(Request $request)
    {
        // Counts for ongoing, pending, denied, verified
        $ongoingCount = Application::where('status', 'ongoing')->count();
        $pendingCount = Application::where('status', 'pending')->count();
        $deniedCount = Application::where('status', 'denied')->count();
        $verifiedCount = Application::where('status', 'verified')->count();
        $passedCount = Application::where('status', 'passed')->count();
    
        // Initialize the query and exclude 'passed' applications
        $query = Application::where('status', '!=', 'passed');
    
        // Check if the search term is provided
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('id', 'like', '%' . $searchTerm . '%')
                    ->orWhere('facility', 'like', '%' . $searchTerm . '%')
                    ->orWhere('province_city', 'like', '%' . $searchTerm . '%')
                    ->orWhere('city', 'like', '%' . $searchTerm . '%')
                    ->orWhere('address', 'like', '%' . $searchTerm . '%')
                    ->orWhere('contact_number', 'like', '%' . $searchTerm . '%')
                    ->orWhere('designation', 'like', '%' . $searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }
    
        // Check if the status filter is applied
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }
    
        // Paginate the filtered applications
        $applications = $query->simplePaginate(10)->appends($request->except('page'));
    
        return view('pdoho.applications', compact('applications', 'ongoingCount', 'pendingCount', 'verifiedCount', 'deniedCount', 'passedCount'));
    }
    
    public function showStatus()
    {
        
        $user = Auth::user();
        
        // Fetch only the user's applications
        $statuses = Application::where('user_id', $user->id)->get(); 

        // Convert schedule to Carbon instance
        foreach ($statuses as $application) {
            if ($application->visit_date) {
                $application->visit_date = Carbon::parse($application->visit_date);
            }
        }

        $pendingCount = Application::where('status', 'pending')->count();
        $ongoingCount = Application::where('status', 'ongoing')->count();
        $deniedCount = Application::where('status', 'denied')->count();
        $verifiedCount = Application::where('status', 'verfied')->count();
        $passedCount= Application::where('status', 'passed')->count();
        return view('client.applicationstatus', compact('statuses','pendingCount', 'ongoingCount', 'deniedCount', 'verifiedCount','passedCount'));
    } 

    //for pdoho viewing all the list
    public function viewApplicationslist()
    {
        $pendingCount = Application::where('status', 'pending')->count();
        $ongoingCount = Application::where('status', 'ongoing')->count();
        $deniedCount = Application::where('status', 'denied')->count();
        $verifiedCount = Application::where('status', 'verified')->count();
        $passedCount = Application::where('status', 'passed')->count();
        $applications = Application::where('status', '!=', 'passed')->simplePaginate(10);

       return view('pdoho.applications', compact('applications', 'pendingCount', 'ongoingCount', 'deniedCount', 'verifiedCount','passedCount'));
    }

    public function setSchedule(Request $request, $id)
    {
       
        $request->validate([
            'visit_date' => 'required|date', 
        ]);
    
        // Find the application by ID
        $application = Application::findOrFail($id);
    
        // Set the schedule
        $application->visit_date = $request->visit_date;
        $application->status = 'ongoing'; // Update status to ongoing
        $application->save();
    
        return response()->json(['message' => 'Visitaion Date set successfully!']);
    }
    
    public function deny(Request $request, $id)
    {
        \Log::info($request->all()); // Log all incoming data

        $request->validate([
            'remarks' => 'required|string|max:255', // Validate the remarks
        ]);

        // Find the application by ID
        $application = Application::findOrFail($id);
        
        // Update the status to denied and set remarks
        $application->status = 'denied';
        $application->remarks = $request->remarks; // Store the remarks
        $application->save();
        
        return response()->json(['message' => 'Application denied successfully!']);
    }

    public function verify($id)
    {
        $application = Application::find($id);
        
        if ($application) {
            $application->status = 'verified';
            $application->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Application not found'], 404);
    }
    
    public function destroy($id)
    {
        $application = Application::find($id);
        
        if ($application) {
            $application->delete(); // This will soft delete the application
            return redirect()->back()->with('success', 'Application deleted successfully.');
        }

        return redirect()->back()->with('error', 'Application not found.');
    }
    public function edit($id)
    {
        // Fetch the application details by ID
        $application = Application::findOrFail($id);

        // Pass the application details to the edit view
        return view('client.edit', compact('application'));
    }

    public function update(Request $request, $id)
    {
        // Fetch the application details by ID
        $application = Application::findOrFail($id);

        // Validate the input data
        $validatedData = $request->validate([
            'facility' => 'required|string|max:255',
            'province_city' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'contact_number' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'intent_upload' => 'nullable|file|mimes:pdf,doc,docx|max:8192', // 8MB limit
            'assessment_upload' => 'nullable|file|mimes:pdf,doc,docx|max:8192',
        ]);

        // Update fields
        $application->facility = $validatedData['facility'];
        $application->province_city = $validatedData['province_city'];
        $application->city = $validatedData['city'];
        $application->contact_number = $validatedData['contact_number'];
        $application->email = $validatedData['email'];

        // Handle file uploads
        if ($request->hasFile('intent_upload')) {
            $path = $request->file('intent_upload')->store('public/uploads');
            $application->intent_upload = str_replace('public/', '', $path);
        }

        if ($request->hasFile('assessment_upload')) {
            $path = $request->file('assessment_upload')->store('public/uploads');
            $application->assessment_upload = str_replace('public/', '', $path);
        }

        // Save changes
        $application->save();

        // Redirect back with a success message
        return redirect()->route('client.applicationstatus', $application->id)->with('success', 'Application updated successfully.');
    }



}
