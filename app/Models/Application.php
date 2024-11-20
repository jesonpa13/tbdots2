<?php

namespace App\Models;

// Import necessary traits and classes from Laravel's Eloquent ORM.
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

// Define the Application model, extending Laravel's base Model class.
class Application extends Model
{
    // Use the HasFactory trait to allow factory-based testing and seeding.
    use HasFactory;

    /**
     * Define which attributes are mass-assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'user_id',             
        'province_city', 
        'city',       
        'facility',            
        'address',            
        'head_of_unit',        
        'designation',         
        'contact_number',      
        'email',               
        'intent_upload',       
        'assessment_upload',   
        'status',
        'remarks', 
        'ntp_remarks',            
        'registration_no',
        'date_renewal',
        'date_expired',
    ];

    /**
     * Define the relationship between the Application and User models.
     * 
     * An Application belongs to a User.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        // Each application is associated with one user, so we use belongsTo.
        return $this->belongsTo(User::class);
    }
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
    // Assuming a facility has many applications
    public function applications()
    {
        return $this->hasMany(Application::class);
    }
    /**
     * Define the relationship between the Application and head_of_unit.
     * 
     * An Application can have a head of unit who is also a User.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function headOfUnit()
    {
        // Define relationship with a specific head_of_unit user.
        return $this->belongsTo(User::class, 'head_of_unit_id'); // Assumes 'head_of_unit_id' column exists
    }
    public function getDateRenewalAttribute($value)
{
    return Carbon::parse($value)->format('m-d-Y');
}

public function getDateExpiredAttribute($value)
{
    return Carbon::parse($value)->addYears(3)->format('m-d-Y');
}

}
