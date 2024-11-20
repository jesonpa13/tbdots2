<?php

namespace App\Exports;

// Import necessary models and classes
use App\Models\Facilities; // Reference to Facilities model, though not used directly in this code
use Maatwebsite\Excel\Concerns\FromCollection; // Import FromCollection interface for exporting data in collections

// Define the FacilitiesExport class for exporting facility data
class FacilitiesExport implements FromCollection
{
    /**
     * Method to return a collection of data for export.
     * 
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Fetch data from the 'applications' table using Laravel's DB facade
        return \DB::table('applications')  // Access the 'applications' table in the database
            ->select('facility', 'registration_no', 'date_renewal', 'date_expired') // Select specific columns for export
            ->where('status', 'passed') // Filter data where the status is 'passed'
            ->get(); // Retrieve the result as a collection
    }
}
