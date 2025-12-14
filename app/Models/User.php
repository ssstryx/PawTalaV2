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
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'barangay',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function adminProfile()
    {
        return $this->hasOne(AdminProfile::class);
    }

    public function userProfile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function getNameAttribute()
    {
        if ($this->role === 'admin' || $this->role === 'super_admin') {
            return "{$this->adminProfile?->first_name} {$this->adminProfile?->last_name}";
        } else {
            return "{$this->userProfile?->first_name} {$this->userProfile?->last_name}";
        }
    }
}
