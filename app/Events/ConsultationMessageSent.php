<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


use App\Models\ConsultationMessage;
use App\Models\User;

class ConsultationMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $user;
    public $consultationId;

    /**
     * Membuat instance event baru.
     * @param ConsultationMessage $message
     * @param User $user
     * @param int $consultationId
     */
    public function __construct(ConsultationMessage $message, User $user, $consultationId)
    {
        $this->message = $message;
        $this->user = $user;
        $this->consultationId = $consultationId;
    }

    /**
     * Channel broadcast privat untuk konsultasi tertentu.
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('consultation.' . $this->consultationId),
        ];
    }

    /**
     * Nama event broadcast (opsional, default: nama class)
     */
    public function broadcastAs()
    {
        return 'ConsultationMessageSent';
    }
}
