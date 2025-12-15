<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SetUserActiveOnVerified
{
    public function handle(Verified $event)
    {
        // When email is verified, set the user's status to active
        $user = $event->user;

        // Only update if they are currently inactive (which they should be)
        if (!$user->is_active) {
            $user->is_active = true;
            $user->save();
        }
    }
}