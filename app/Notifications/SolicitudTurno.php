<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class SolicitudTurno extends Notification
{
    use Queueable;
    protected $paciente;
    protected $descripcion;
    protected $imagenes;
    protected $turno;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($descripcion, $imagenes, $paciente, $turno)
    {
        $this->descripcion = $descripcion;
        $this->imagenes = $imagenes;
        $this->paciente = $paciente->nombre . ' ' . $paciente->apellido;
        $this->turno = $turno;
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
            ->subject('Solicitud de ' . $this->paciente)
            ->greeting('Buenos días')
            ->line('Hemos recibido una solicitud de consulta')
            ->line('Paciente: ' . $this->paciente)
            ->line('Descripción: ' . $this->descripcion)
            ->line('Fecha: ' . $this->turno->fecha)
            ->line('Horario: ' . $this->turno->horario)
            ->line(new HtmlString("<hr>"))
            ->action('Ir al dashboard', url('https://sonrisafeliz.herokuapp.com/dashboard/turnoadmin'))
            ->salutation('Saludos. CMS Sonrisa Feliz');

        foreach ($this->imagenes as $imagen) {
            $data = base64_decode($imagen['image']);
            $mensaje->attachData($data, $imagen['filename']);
        }

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
