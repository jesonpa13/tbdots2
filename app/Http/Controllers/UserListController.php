<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserListController extends Controller
{
    public function index(){
        return view('admin.accountlist');
    }
}
