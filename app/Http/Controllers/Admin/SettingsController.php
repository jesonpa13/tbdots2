<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        // Fetch and return a view with system settings
        return view('admin.settings.index'); // Make sure this view exists
    }

    public function edit()
    {
        return view('admin.settings');
    }

    public function update(Request $request)
    {
        // Handle the update logic for system settings (e.g., save settings to database)
        return redirect()->route('admin.settings')->with('success', 'Settings updated successfully!');
    }
}
