<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function dashboard()
    {
         
        $user = Auth::user();
        
        // Fetch only the user's applications
        $statuses = Application::where('user_id', $user->id)->get(); 

        $pendingCount = Application::where('status', 'pending')->count();
        $ongoingCount = Application::where('status', 'ongoing')->count();
        $deniedCount = Application::where('status', 'failed')->count();
        $verifiedCount = Application::where('status', 'passed')->count();
       
        return view('client.dashboard', compact('pendingCount', 'ongoingCount', 'deniedCount', 'verifiedCount'));
    }
    public function client()
    {
         
        $user = Auth::user();
        
        // Fetch only the user's applications
        $statuses = Application::where('user_id', $user->id)->get(); 

        $pendingCount = Application::where('status', 'pending')->count();
        $ongoingCount = Application::where('status', 'ongoing')->count();
        $deniedCount = Application::where('status', 'failed')->count();
        $verifiedCount = Application::where('status', 'passed')->count();
       
        return view('client.assessment', compact('pendingCount', 'ongoingCount', 'deniedCount', 'verifiedCount'));
    }

    public function sendrequest()
    {
        return view('client.sendrequest');
    }
}
