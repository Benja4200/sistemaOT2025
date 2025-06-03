<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class NuevaOrdenNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $orden;

    public function __construct($orden)
    {
        $this->orden = $orden;
    }

    public function via($notifiable)
    {
        // Usamos el canal database y broadcast
        return ['database','broadcast', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'mensaje'  => "Tienes una nueva orden de trabajo: " . $this->orden->numero_ot,
            'orden_id' => $this->orden->numero_ot,
            'estado'   => $this->orden->cod_estado_ot,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'mensaje'  => "Tienes una nueva orden de trabajo: " . $this->orden->numero_ot,
            'orden_id' => $this->orden->numero_ot,
            'estado'   => $this->orden->cod_estado_ot,
        ]);
    }
    
    public function toMail($notifiable)
    {
        // Logueamos los roles del usuario para depuración
        $roles = implode(', ', $notifiable->getRoleNames()->toArray());
        Log::info('Notifiable roles: ' . $roles);
        Log::info('Email del notifiable: ' . $notifiable->email);
        if ($notifiable->hasRole('Tecnicos')) {
            return (new MailMessage)
                        ->subject('Nueva Orden de Trabajo para Técnicos')
                        ->markdown('emails.nuevaOrdenTecnico', ['orden' => $this->orden, 'notifiable' => $notifiable]);
        } elseif ($notifiable->hasRole('Ejecutivo')) {
            return (new MailMessage)
                        ->subject('Nueva Orden de Trabajo Asignada')
                        ->markdown('emails.nuevaOrdenEjecutivo', ['orden' => $this->orden, 'notifiable' => $notifiable]);
        } else {
            return (new MailMessage)
                        ->subject('Nueva Orden de Trabajo')
                        ->markdown('emails.nuevaOrden', ['orden' => $this->orden, 'notifiable' => $notifiable]);
        }
    }
}
