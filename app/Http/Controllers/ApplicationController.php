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
    $deniedCount = Application::where('status', 'failed')->count();
    $verifiedCount = Application::where('status', 'verified')->count();
    $passedCount = Application::where('status', 'passed')->count();
    return view('pdoho.dashboard', compact('applications', 'ongoingCount', 'pendingCount', 'deniedCount', 'verifiedCount','passedCount'));
}

public function index(Request $request)
{
    $query = Application::query();

    // Check if the search term is provided
    if ($request->filled('search')) {
        $searchTerm = $request->input('search');
        $query->where('id', 'like', '%' . $searchTerm . '%')
              ->orWhere('facility', 'like', '%' . $searchTerm . '%')
              ->orWhere('province_city', 'like', '%' . $searchTerm . '%')
              ->orWhere('address', 'like', '%' . $searchTerm . '%')
              ->orWhere('contact_number', 'like', '%' . $searchTerm . '%')
              ->orWhere('designation', 'like', '%' . $searchTerm . '%')
              ->orWhere('email', 'like', '%' . $searchTerm . '%');
    }

    // Check if the status filter is applied
    if ($request->filled('status') && $request->input('status') !== 'all') {
        $query->where('status', $request->input('status'));
    }

    $applications = $query->simplePaginate(10)->appends($request->except('page'));

    // Counts for ongoing, pending, denied, verified
    $ongoingCount = Application::where('status', 'ongoing')->count();
    $pendingCount = Application::where('status', 'pending')->count();
    $deniedCount = Application::where('status', 'failed')->count();
    $verifiedCount = Application::where('status', 'passed')->count();
    $passedCount = Application::where('status', 'passed')->count();
    return view('pdoho.applications', compact('applications', 'ongoingCount', 'pendingCount', 'deniedCount', 'verifiedCount','passedCount'));
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
        $deniedCount = Application::where('status', 'failed')->count();
        $verifiedCount = Application::where('status', 'passed')->count();
        $passedCount= Application::where('status', 'passed')->count();
        return view('client.applicationstatus', compact('statuses','pendingCount', 'ongoingCount', 'deniedCount', 'verifiedCount','passedCount'));
    } 

    //for pdoho viewing all the list
    public function viewApplicationslist()
    {
        $pendingCount = Application::where('status', 'pending')->count();
        $ongoingCount = Application::where('status', 'ongoing')->count();
        $deniedCount = Application::where('status', 'failed')->count();
        $verifiedCount = Application::where('status', 'verified')->count();
        $passedCount = Application::where('status', 'passed')->count();
       $applications = Application::simplePaginate(10);  
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

}
