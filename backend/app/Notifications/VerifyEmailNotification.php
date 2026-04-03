<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

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
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject(__('Vérifiez votre adresse e-mail - Wasitni'))
            ->greeting('Bonjour ' . $notifiable->first_name . ' !')
            ->line(__('Bienvenue chez Wasitni ! Nous sommes ravis de vous compter parmi nous.'))
            ->line(__('Voici votre code de vérification à 6 chiffres :'))
            ->line('**' . $this->code . '**')
            ->line(__('Veuillez saisir ce code dans l\'application pour activer votre compte.'))
            ->action(__('Ou cliquez ici pour vérifier'), $verificationUrl)
            ->line(__('Ce code expirera dans 15 minutes.'))
            ->line(__('Si vous n\'avez pas créé de compte, aucune autre action n\'est requise.'))
            ->salutation(__('Cordialement,') . "\n" . config('app.name'));
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        $url = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        return str_replace(url('/api/v1/email/verify'), config('app.frontend_url') . '/verify-email', $url);
    }
}
