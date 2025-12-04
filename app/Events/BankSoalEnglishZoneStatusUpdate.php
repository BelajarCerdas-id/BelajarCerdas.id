<?php

namespace App\Events;

use Illuminate\Support\Collection;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BankSoalEnglishZoneStatusUpdate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public $bankSoalStatusUpdate;
    public function __construct(Collection $bankSoalStatusUpdate)
    {
        // jika yang di broadcast adalah sebuah collection
        $this->bankSoalStatusUpdate = $bankSoalStatusUpdate;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): Channel
    {
        return new Channel('bankSoalStatusUpdate');
    }

    public function broadcastAs(): string
    {
        return 'bank.soal.status.update';
    }
}