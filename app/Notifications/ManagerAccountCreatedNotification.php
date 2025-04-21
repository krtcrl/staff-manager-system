<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ManagerAccountCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $password;

    /**
     * Create a new notification instance.
     *
     * @param string $password
     */
    public function __construct($password)
    {
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Manager Account Has Been Created')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your manager account has been created by the administrator.')
            ->line('Here are your login details:')
            ->line('Manager #: ' . $notifiable->manager_number)
            ->line('Email: ' . $notifiable->email)
            ->line('Password: ' . $this->password)
            ->action('Login to Your Account', route('login'))
            ->line('Please change your password after logging in.')
            ->line('As a manager, you have additional system privileges. Keep your credentials secure.')
            ->salutation('Regards, ' . config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}