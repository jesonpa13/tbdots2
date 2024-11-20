<?php

// Define the namespace for this middleware class, which helps Laravel organize and load the class.
namespace App\Http\Middleware;

// Import necessary classes for handling requests and authentication.
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

// Define the AdminMiddleware class.
class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * 
     * This function checks if the user is authenticated and has an 'admin' role.
     * If the user does not meet these conditions, they are redirected to the home page.
     * 
     * @param  \Illuminate\Http\Request  $request  The current HTTP request.
     * @param  \Closure  $next  The next middleware or request handler.
     * @return mixed  A response or the result of the next middleware.
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->user_type === 'admin') {
            return $next($request);
        }
    
        return redirect()->route('dashboard');  // Redirect non-admins to their main dashboard
    }
}
