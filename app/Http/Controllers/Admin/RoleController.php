<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        // Fetch and return a view with roles
        return view('admin.roles.index'); // Make sure this view exists
    }

    // Add other methods for creating, editing, updating, and deleting roles
}
