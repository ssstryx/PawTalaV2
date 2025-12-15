<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pet extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'veterinarian_id',
        'owner_name',
        'name',
        'species',
        'sex',
        'breed',
        'birthdate',
        'weight',
        'color',
        'vaccination_status',
        'photo_path',
        'registration_date',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'registration_date' => 'date',
    ];

    // --- THIS IS THE MISSING PART THAT FIXES THE NAME ---
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // ----------------------------------------------------

    public function getFormattedIdAttribute()
    {
        return 'PT-' . str_pad($this->id, 3, '0', STR_PAD_LEFT);
    }

    public function veterinarian()
    {
        return $this->belongsTo(Veterinarian::class, 'veterinarian_id');
    }
}