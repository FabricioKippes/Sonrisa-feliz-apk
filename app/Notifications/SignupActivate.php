<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class SignupActivate extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {

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
        $urlToken = url(env('FRONT_URL').'/activate-account/'.$notifiable->activation_token);
        $urlNotToken = url(env('FRONT_URL').'/activate-account/');

        return (new MailMessage)
            ->subject('Confirmar cuenta')
            ->line(new HtmlString('<h1>Bienvenido a Sonrisa Feliz!</h1>'))
            ->line('Para completar su registro por favor haga click en el siguiente botón')
            ->action('Confirmar correo', url($urlToken))
            ->line(new HtmlString('Si tiene problemas para activar su cuenta por favor visite: <a href="' . url($urlNotToken) . '">' . url($urlNotToken) . '</a> e ingrese el siguiente codigo: <b>' . $notifiable->activation_token . '</b>'))
            ->line('Muchas gracias por confiar en nosotros!');
    }
}
