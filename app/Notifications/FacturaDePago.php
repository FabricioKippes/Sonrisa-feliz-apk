<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FacturaDePago extends Notification
{
    use Queueable;
    protected $archivo;
    protected $ruta;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($archivo, $ruta)
    {
        $this->archivo = $archivo;
        $this->ruta = $ruta;
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
        $mensaje = (new MailMessage)
            ->greeting('Buenos días')
            ->line('Su pago se ha realizado con éxito, adjuntamos su factura')
            ->salutation('Atentamente, Consultorio Sonrisa Feliz.')
            ->attach(
                $this->ruta,
                [
                    'as' => $this->archivo,
                    'mime' => 'text/pdf',
                ]
            );

        return $mensaje;
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
