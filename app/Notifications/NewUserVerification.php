<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class NewUserVerification extends Notification
{
    use Queueable;

    public $rawPassword;

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
        $verificationUrl = URL::temporarySignedRoute(
            'custom.verification.verify',
            Carbon::now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        return (new MailMessage)
                    ->subject('Verify Your Pawtala Account')
                    ->greeting('Hello ' . $notifiable->first_name . ',')
                    ->line('Welcome to Pawtala! You have been registered by an admin.')
                    ->line('Before you can access the system, you must verify your email address.')
                    ->line('Your Login Credentials:')
                    ->line('Email: ' . $notifiable->email)
                    ->line('Password: ' . $this->rawPassword)
                    ->action('Verify Email & Login', $verificationUrl)
                    ->line('Thank you for using our application!');
    }
}
