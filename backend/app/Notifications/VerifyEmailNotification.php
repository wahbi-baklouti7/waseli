<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $code)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Vérifiez votre adresse e-mail - Wasitni'))
            ->greeting('Bonjour ' . $notifiable->first_name . ' !')
            ->line(__('Bienvenue chez Wasitni ! Nous sommes ravis de vous compter parmi nous.'))
            ->line(__('Voici votre code de vérification à 6 chiffres :'))
            ->line('**' . $this->code . '**')
            ->line(__('Veuillez saisir ce code dans l\'application pour activer votre compte.'))
            ->line(__('Ce code expirera dans 15 minutes.'))
            ->line(__('Si vous n\'avez pas créé de compte, aucune autre action n\'est requise.'))
            ->salutation(__('Cordialement,') . "\n" . config('app.name'));
    }
}
