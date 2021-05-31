<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class PasswordResetRequest extends Notification
{
    use Queueable;
    protected $token;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
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
        $urlToken = url(env('FRONT_URL') . '/password-reset/' . $this->token);

        return (new MailMessage)
            ->subject('Sonrisa Feliz - restablecer contraseña')
            ->greeting('Buenos días')
            ->line("Hemos recibido una solicitud para restablecer su contraseña, por favor haga click en el siguiente botón para continuar: ")
            ->action(__("Restablecer Contraseña"), url($urlToken))
            ->line(new HtmlString("Si tiene algún problema con el botón, copie y pegue esta dirección en el navegador". '&nbsp; <a href="' . url($urlToken) . '">' . url($urlToken) . '</a>'))
            ->line(__("Este token expirará en 20 minutos"))
            ->line(__("Gracias por utilizar nuestros servicios!"))
            ->salutation('Atentamente. Sonrisa Feliz');
    }
}
