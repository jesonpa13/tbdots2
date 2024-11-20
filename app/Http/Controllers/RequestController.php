<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;

class RequestController extends Controller
{
    public function index()
    {
        return view('client.sendrequest');
    }

    public function applicationStatus()
    {
        return view('client.applicationstatus');
    }

    public function store(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'province_city' => 'required',
            'city' => 'required',
            'facility' => 'required',
            'address' => 'required',
            'head_of_unit' => 'required',
            'designation' => 'required',
            'contact_number' => 'required',
            'email' => 'required|email',
            'intent_upload' => 'required|file|mimes:pdf,docx,doc|max:8192', // Added max file size validation
            'assessment_upload' => 'required|file|mimes:pdf,docx,doc|max:8192', // Added max file size validation
        ]);

        // Attempt to store the uploaded files
        try {
            $intentUploadPath = $request->file('intent_upload')->storeAs('intent_uploads', $request->file('intent_upload')->getClientOriginalName(), 'public');
            $assessmentUploadPath = $request->file('assessment_upload')->storeAs('assessment_uploads', $request->file('assessment_upload')->getClientOriginalName(), 'public');
        } catch (\Exception $e) {
            \Log::error('File upload error: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'File upload failed: ' . $e->getMessage()]);
        }

        // Create a new application instance
        $statuses = new Application();
        $statuses->user_id = auth()->id();
        $statuses->province_city = $request->input('province_city');
        $statuses->city = $request->input('city');
        $statuses->facility = $request->input('facility');
        $statuses->address = $request->input('address');
        $statuses->head_of_unit = $request->input('head_of_unit');
        $statuses->designation = $request->input('designation');
        $statuses->contact_number = $request->input('contact_number');
        $statuses->email = $request->input('email');
        $statuses->intent_upload = $intentUploadPath;
        $statuses->assessment_upload = $assessmentUploadPath;
        $statuses->status = 'pending';
        $statuses->remarks = 'No Remarks';

        // Save the application instance
        if ($statuses->save()) {
            \Log::info('Application saved successfully!');
            // Fetch all applications of the authenticated user
            $applications = Application::where('user_id', auth()->id())->get();
        
            // Pass the collection to the view
            return view('client.applicationstatus', ['statuses' => $applications])
                ->with('success', 'Request sent successfully!');
        }
        
    }
}