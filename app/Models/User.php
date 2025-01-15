<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * These fields can be mass-assigned during the creation or update of a User object.
     * For security, only include fields you want to allow in bulk operations.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type', // Identifies the user's role, e.g., 'admin', 'client', 'supervisor'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * These fields will be hidden when the user object is converted to an array or JSON.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token', // Token used to remember the user's session
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * Casting allows Laravel to automatically convert data types when the model is accessed.
     * In this case, the 'email_verified_at' timestamp is cast to a `datetime` object.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Check if the user has an 'admin' role.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->user_type === 'admin';
    }

    /**
     * Check if the user has a 'client' role.
     *
     * @return bool
     */
    public function isClient()
    {
        return $this->user_type === 'client';
    }

    /**
     * Check if the user has a 'supervisor' role.
     *
     * @return bool
     */
    public function isSupervisor()
    {
        return $this->user_type === 'supervisor';
    }
    public function isActive()
    {
        return $this->status === 'active';
    }
    // Define the relationship with AdditionalInformation
    public function additionalInformation()
    {
        return $this->hasOne(AdditionalInformation::class);
    }
    
}
