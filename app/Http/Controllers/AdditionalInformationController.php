<?php

namespace App\Http\Controllers;

use App\Models\AdditionalInformation;
use Illuminate\Http\Request;

class AdditionalInformationController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'province_city' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'facility' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'contact_number' => 'required|string|max:255',
            'head_of_unit' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);
    
        // Create a new AdditionalInformation record
        AdditionalInformation::create([
            'user_id' => auth()->id(), // Assuming you want to associate it with the authenticated user
            'province_city' => $request->input('province_city'),
            'city' => $request->input('city'),
            'facility' => $request->input('facility'),
            'address' => $request->input('address'),
            'contact_number' => $request->input('contact_number'),  
            'head_of_unit' => $request->input('head_of_unit'), 
            'designation' => $request->input('designation'),
            'email' => $request->input('email'),
        ]);
    
        // Redirect to a different route after successful submission
        return redirect()->route('client.dashboard')->with('success', 'Additional information saved successfully.');
    }
}