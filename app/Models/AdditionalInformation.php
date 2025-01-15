<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'facility', 'province_city', 'city', 'address', 'contact_number', 'head_of_unit' ,'designation', 'email',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
