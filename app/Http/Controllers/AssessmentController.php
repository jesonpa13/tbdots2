<?php
// app/Http/Controllers/SelfAssessmentController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function show()
    {
        return view('client.assessment');  // Make sure this view exists in resources/views
    }
    public function downloadGuide()
    {
        $filePath = public_path('docs/self_assessment_tool_guide.pdf');
        return response()->download($filePath);
    }
    public function surveyTool()
    {
        // Logic for self assessment survey tool
        return view('self_assessment_survey_tool'); // Create this view as needed
    }
    
    public function showpdoho()
    {
        return view('pdoho.assessment');  // Make sure this view exists in resources/views
    }
}
?>