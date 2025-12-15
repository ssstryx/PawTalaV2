<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Veterinarian extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'clinic_name',
        'veterinarian_name',
        'license_number',
        'contact_number',
        'email',
        'clinic_address',
        'clinic_hours',
        'specialization',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}