<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Notifications\Notification;

class AppointmentAssigned extends Notification
{
    /**
     * @param  string  $kind  'new' when booked for this dentist, 'rescheduled' when date/time changed
     */
    public function __construct(public Appointment $appointment, public string $kind = 'new')
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $a = $this->appointment->loadMissing(['patient', 'service']);

        return [
            'kind' => $this->kind,
            'appointment_id' => $a->id,
            'appointment_no' => $a->appointment_no,
            'patient' => $a->patient->full_name ?? '-',
            'service' => $a->service->name ?? '-',
            'date' => $a->appointment_date->format('Y-m-d'),
            'time' => substr($a->start_time, 0, 5),
        ];
    }
}
