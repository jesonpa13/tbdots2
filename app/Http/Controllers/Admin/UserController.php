<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    // Display a listing of users
    public function index(Request $request)
    {
        // Get the search query from the request
        $search = $request->get('search');

        // Fetch users with the search query applied
        $users = User::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->get();

        return view('admin.users.index', compact('users'));
    }

    // Show the form for creating a new user
    public function create()
    {
        $header = 'Create User'; // Set your header for create view
        return view('admin.users.create', compact('header')); // Pass header
    }

    // Store a newly created user in storage
    public function store(Request $request)
    {
        // Validate the user input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'user_type' => ['required', 'string'], // Include user_type for roles
        ]);

        // Create a new user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
        ]);

        // Redirect back with success message
        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    // Show the form for editing a specific user
    public function edit(User $user)
    {
        $header = 'Edit User'; // Set a header for the edit view
        return view('admin.users.edit', compact('user', 'header')); // Pass user and header to the view
    }

    // Update a specific user in storage
    public function update(Request $request, User $user)
    {
        // Validate the user input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'user_type' => ['required', 'string'], // Include user_type for roles
        ]);

        // Update user details
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'user_type' => $request->user_type,
        ]);

        // Optionally, update password if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $user->password = Hash::make($request->password);
            $user->save();
        }

        // Redirect back with success message
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    // Remove a specific user from storage
    public function destroy(User $user)
    {
        $user->delete(); // Delete the user

        // Redirect back with success message
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function approve(User $user)
{
    if ($user->status === 'pending') {
        $user->status = 'active'; // Automatically set status to active when approved
        $user->save();
        return redirect()->route('admin.users.index')->with('success', 'User approved successfully.');
    }

    return redirect()->route('admin.users.index')->with('error', 'User is not in a pending state.');
}

public function activate(User $user)
{
    if ($user->status === 'inactive') {
        $user->status = 'active';
        $user->save();
        return redirect()->route('admin.users.index')->with('success', 'User activated successfully.');
    }

    return redirect()->route('admin.users.index')->with('error', 'User is not in an inactive state.');
}

public function deactivate(User $user)
{
    if ($user->status === 'active') {
        $user->status = 'inactive';
        $user->save();
        return redirect()->route('admin.users.index')->with('success', 'User deactivated successfully.');
    }

    return redirect()->route('admin.users.index')->with('error', 'User is not in an active state.');
}

    // Update user status using the new method
    public function updateStatus(User $user, Request $request)
    {
        $user->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'User status updated successfully.');
    }
 
}
