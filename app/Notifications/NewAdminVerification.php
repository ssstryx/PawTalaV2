<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class NewAdminVerification extends Notification
{
    use Queueable;

    public $rawPassword; // We will pass the password so they know what to login with

    public function __construct($rawPassword)
    {
        $this->rawPassword = $rawPassword;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Generate a Signed URL that expires in 60 minutes
        $verificationUrl = URL::temporarySignedRoute(
            'custom.verification.verify',
            Carbon::now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        return (new MailMessage)
                    ->subject('Verify Your Pawtala Admin Account')
                    ->greeting('Hello ' . $notifiable->first_name . ',')
                    ->line('You have been registered as a Barangay Admin.')
                    ->line('Before you can access the system, you must verify your email address.')
                    ->line('Your Login Credentials:')
                    ->line('Email: ' . $notifiable->email)
                    ->line('Password: ' . $this->rawPassword) // Showing password one time
                    ->action('Verify Email & Login', $verificationUrl)
                    ->line('Thank you for using our application!');
    }
}