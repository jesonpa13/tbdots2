<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     * 
     * @return View The login view page
     */
    public function create(): View
    {
        // Return the login view to the user
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     * 
     * @param  LoginRequest $request Handles and validates login request
     * @return RedirectResponse Redirects based on user type after login
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
         // Get the authenticated user
    $user = auth()->user();

    // Check if the user status is inactive
    if ($user->status === 'inactive') {
        // Log the user out immediately
        Auth::logout();

        // Redirect back to the login page with an error message
        return redirect()->route('login')->withErrors([
            'status' => 'Your account is inactive. Please contact support for assistance.',
        ]);
    }
        $request->session()->regenerate();

    $user = auth()->user();

    switch ($user->user_type) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'client':
            return redirect()->route('client.dashboard');
        case 'inspector':
            return redirect()->route('pdoho.dashboard');
        case 'supervisor':
            return redirect()->route('ntpmanager.dashboard');
        default:
            return redirect()->intended(RouteServiceProvider::HOME);
    }
    }

    /**
     * Destroy an authenticated session.
     * 
     * @param  Request $request Manages the request for logging out
     * @return RedirectResponse Redirects to login or home page after logout
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Log out the user from the current session
        Auth::guard('web')->logout();

        // Invalidate the session to remove all session data
        $request->session()->invalidate();

        // Regenerate the CSRF token to ensure a fresh token for future requests
        $request->session()->regenerateToken();

        // Redirect to the login page or home page after logout
        return redirect('/');
    }
}
