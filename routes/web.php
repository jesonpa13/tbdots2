<?php

// Importing necessary controllers for handling various application functionalities
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Client\ClientDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AuthenticatedSessionController; // Ensure this is imported
use App\Http\Controllers\Admin\SettingsController; // Ensure SettingsController is imported
use App\Http\Controllers\NTPManagerController;
use App\Http\Controllers\UserListController;
use App\Http\Controllers\RequestController;




// Registration routes
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

// Login routes
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

//application status routes(copy)
Route::get('/application-status', [ApplicationController::class, 'showStatus'])->name('application.status');

//Search Route
Route::get('/pdoho/applications', [ApplicationController::class, 'index'])->name('applications.index');
// Soft delete route
Route::delete('/applications/{id}', [ApplicationController::class, 'destroy'])->name('applications.destroy');
//routes for setting schedule:
Route::post('/applications/{id}/set-schedule', [ApplicationController::class, 'setSchedule']);
//routes for deny an schedule
Route::post('/applications/{id}/deny', [ApplicationController::class, 'deny'])->name('applications.deny');
//Route to verify an application
Route::post('/applications/{id}/verify', [ApplicationController::class, 'verify'])->name('applications.verify');

//routes for assessment form and tool(copy)
// Change this route to avoid conflicts with layouts
Route::get('client/assessment', [AssessmentController::class, 'show'])->name('client.assessment');


Route::get('pdoho/assessment', [AssessmentController::class, 'showpdoho'])->name('assessment.assessment');

//routes for downloading survey form and tool(copy)
Route::get('/downloads/self-assessment-form', function () {
    return response()->download(public_path('downloads/self_assessment_form.doc'));
})->name('download.selfAssessmentForm');

Route::get('/downloads/self-assessment-tool', function () {
    return response()->download(public_path('downloads/self_assessment_tool_guide.pdf'));
})->name('download.selfAssessmentTool');


// Handle the submission of a new application
Route::post('/client.sendrequest', [RequestController::class, 'store'])->name('client.sendrequest.store');

//sow the send request form
Route::get('/layouts.userlayout', [ClientController::class, 'sendrequest'])->name('client.sendrequest');

//route for viewing application in pdoho POV
Route::get('/layouts.pdoholayout', [ApplicationController::class, 'viewApplicationslist'])->name('pdoho.applications');


/*
|-------------------------------------------------------------------------- 
| Web Routes 
|-------------------------------------------------------------------------- 
| 
| Here is where you can register web routes for your application. These 
| routes are loaded by the RouteServiceProvider and all of them will be 
| assigned to the "web" middleware group. Make something great! 
| 
*/

// Admin routes - accessible only to users with 'admin' role
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('admin/settings', [SettingsController::class, 'edit'])->name('admin.settings');
    Route::put('admin/settings', [SettingsController::class, 'update'])->name('admin.settings.update');
    Route::post('/admin/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('admin.users.deactivate');
    Route::post('/admin/users/{user}/activate', [UserController::class, 'activate'])->name('admin.users.activate');
});

// NTP Manager Dashboard Route - accessible to authenticated users
Route::middleware('auth')->group(function () {
    // Route to view the NTP manager dashboard
    Route::get('/ntpmanager/dashboard', [NTPManagerController::class, 'dashboard'])->name('ntpmanager.dashboard');

    // Applications routes for NTP Manager
    Route::prefix('ntpmanager/applications')->name('ntpmanager.applications.')->group(function () {
        Route::get('/', [NTPManagerController::class, 'applications'])->name('index');
        Route::get('/{application}', [NTPManagerController::class, 'show'])->name('show');
        Route::post('/{application}/set-schedule', [NTPManagerController::class, 'setSchedule'])->name('setSchedule');
        Route::post('/{application}/update-status', [NTPManagerController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/{application}/confirm-failed-status', [NTPManagerController::class, 'confirmFailedStatus'])->name('confirmFailedStatus');
        Route::get('/{application}/generate', [NTPManagerController::class, 'generate'])->name('generate');
        Route::get('/{application}/preview', [NTPManagerController::class, 'previewCertificate'])->name('preview'); // Route for preview
        Route::post('/{application}/download', [NTPManagerController::class, 'confirmDownload'])->name('download'); // Route for download
    });

    // Facilities route
    Route::get('/ntpmanager/facilities', [NTPManagerController::class, 'facilities'])->name('ntpmanager.facilities');

    // Export routes
    Route::prefix('ntpmanager/export')->name('ntpmanager.export.')->group(function () {
        Route::get('/pdf', [NTPManagerController::class, 'exportPDF'])->name('pdf');
        Route::get('/word', [NTPManagerController::class, 'exportWord'])->name('word');
    });



});

// Client routes - accessible only to users with 'client' role
Route::middleware(['auth', 'client'])->group(function () {
    // Route to view the client dashboard
    Route::get('/client/dashboard', [ClientController::class, 'dashboard'])->name('client.dashboard');

    // Route to show application status to clients
    Route::get('/application-status', [ApplicationController::class, 'showStatus'])->name('application.status');


    // Show the form to send a request
    Route::get('/client.sendrequest', [RequestController::class, 'index'])->name('client.sendrequest');

    // Handle the submission of a new application request
    Route::post('/client.sendrequest', [RequestController::class, 'store'])->name('client.sendrequest.store');

    Route::get('/client.sendrequest', [ApplicationController::class, 'showstatus'])->name('client.applicationstatus');


    // Application routes for storing a new application
    Route::post('/application/store', [ApplicationController::class, 'store'])->name('application.store');
});

// Registration routes for guests (unauthenticated users)
Route::middleware('guest')->group(function () {
    // Route to show the registration form
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    
    // Route to handle the registration form submission
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

// Route to view the PDOHO dashboard
Route::get('/pdoho/dashboard', [ApplicationController::class, 'indexdashboard'])->name('pdoho.dashboard');

// Soft delete route for applications
Route::delete('/applications/{id}', [ApplicationController::class, 'destroy'])->name('applications.destroy');

// Routes for setting schedules for applications
Route::post('/applications/{id}/set-schedule', [ApplicationController::class, 'setSchedule']);

// Route for denying a scheduled application
Route::post('/applications/{id}/deny', [ApplicationController::class, 'deny'])->name('applications.deny');

// Route to verify an application
Route::post('/applications/{id}/verify', [ApplicationController::class, 'verify'])->name('applications.verify');

// Route for viewing applications from the PDOHO perspective
Route::get('/layouts.pdoholayout', [ApplicationController::class, 'viewApplicationslist'])->name('pdoho.applications');


// Public homepage route
Route::get('/', function () {
    return view('welcome');
});     

// Authenticated user routes (common to both admin and client, once logged in)
Route::middleware(['auth', 'verified'])->group(function () {
    // Route to view the main dashboard for authenticated users
    Route::get('/dashboard', function () {
        return view('client.dashboard');
    })->name('dashboard');

    // Route to edit user profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    
    // Route to update user profile information
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Route to delete user profile
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Include the default authentication routes
require __DIR__.'/auth.php';
