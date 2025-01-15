<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;
use App\Models\AdditionalInformation;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function dashboard()
{
    $user = auth()->user();
    
    // Check if the user has submitted additional information
    $hasAdditionalInformation = $user->additionalInformation()->exists();

    // Initialize counts to zero
    $ongoingCount = 0;
    $pendingCount = 0;
    $deniedCount = 0;
    $verifiedCount = 0;

    if ($hasAdditionalInformation) {
        // Counts for ongoing, pending, denied, verified
        $ongoingCount = Application::where('user_id', $user->id)->where('status', 'ongoing')->count();
        $pendingCount = Application::where('user_id', $user->id)->where('status', 'pending')->count();
        $deniedCount = Application::where('user_id', $user->id)->where('status', 'denied')->count();
        $verifiedCount = Application::where('user_id', $user->id)->where('status', 'verified')->count();
    }
    
    return view('client.dashboard', compact('hasAdditionalInformation', 'ongoingCount', 'pendingCount', 'deniedCount', 'verifiedCount'));
}
    

    public function client()
    {
        $user = Auth::user();

        // Fetch the counts for the current user's applications
        $pendingCount = Application::where('user_id', $user->id)->where('status', 'pending')->count();
        $ongoingCount = Application::where('user_id', $user->id)->where('status', 'ongoing')->count();
        $deniedCount = Application::where('user_id', $user->id)->where('status', 'failed')->count();
        $verifiedCount = Application::where('user_id', $user->id)->where('status', 'passed')->count();
    
        return view('client.assessment', compact('pendingCount', 'ongoingCount', 'deniedCount', 'verifiedCount'));
    }


    public function sendrequest()
    {
        // Fetch additional information for the authenticated user
        $additionalInfo = AdditionalInformation::where('user_id', auth()->id())->first();
        
        // Pass the additional information to the view
        return view('client.sendrequest', compact('additionalInfo'));
    }
}
