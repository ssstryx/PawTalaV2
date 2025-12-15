<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'user_name', 'role', 'action', 'details', 'ip_address'
    ];

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper to easily create a log entry
     */
    public static function log($action, $details = null)
    {
        $user = Auth::user();

        self::create([
            'user_id'    => $user ? $user->id : null,
            'user_name'  => $user ? $user->first_name . ' ' . $user->last_name : 'System/Guest',
            'role'       => $user ? $user->role : 'Guest',
            'action'     => $action,
            'details'    => $details,
            'ip_address' => Request::ip(),
        ]);
    }
}