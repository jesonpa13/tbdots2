<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    
    public function dashboard()
    {
        $userCount = User::count(); // Total number of users
        $activeRolesCount = User::distinct('user_type')->count('user_type'); // Count distinct roles
    
        $header = 'Admin Dashboard'; // Set your header text
        return view('admin.dashboard', compact('header', 'userCount', 'activeRolesCount'));
    }
    public function __construct()
    {

        if (auth()->check()) {
            \Log::info('AdminMiddleware is executed for ' . auth()->user()->email);
        } else {
            \Log::warning('Attempt to access admin dashboard without authentication.');
        }

    }   
}
