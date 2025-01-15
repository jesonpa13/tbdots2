<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function create()
    {
        // Return the view for the registration form
        return view('auth.register');
    }
    public function welcomepage()
    {
        // Return the view for the registration form
        return view('welcome');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
    
        // Create the user with a default 'pending' status
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => 'client', // Default role
            'status' => 'pending',   // Default status
        ]);
    
        // Trigger any events if necessary
        event(new Registered($user));
    
        // Redirect with a pending message
        return redirect()->route('register')->with('status', 'Your account has been created and is pending approval. Please wait for admin confirmation.');
    }
    
}

