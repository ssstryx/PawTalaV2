<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'role',
        'contact_number',
        'address',
        'barangay_id', // <--- CHANGED from 'barangay' to 'barangay_id'
        'is_active',
        'owner_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // Link User to Barangay
    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            if ($user->role === 'user') {
                $lastUser = User::where('role', 'user')
                                ->whereNotNull('owner_id')
                                ->orderByRaw('CAST(owner_id AS UNSIGNED) DESC')
                                ->first();
                $user->owner_id = $lastUser ? intval($lastUser->owner_id) + 1 : 1001;
            }
        });
    }
}