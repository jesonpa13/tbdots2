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
    public function store(Request $request)
    {
        // Validate the registration form inputs
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create a new user with the default 'client' role
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => 'client',  // Set the default user_type as 'client'
        ]);

        // Fire the Registered event
        event(new Registered($user));

        // Automatically log in the newly registered user
        Auth::login($user);

        // Redirect clients to their dashboard after successful registration
        return redirect()->route('client.dashboard');
    }
    
}

