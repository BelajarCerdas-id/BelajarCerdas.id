<?php

namespace App\Events;

use App\Models\EnglishZoneBatch;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EventEnglishZoneBatch implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public $EZBatch;
    public function __construct(EnglishZoneBatch $EZBatch)
    {
        // jika yang di broadcast adalah sebuah collection
        $this->EZBatch = $EZBatch;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): Channel
    {
        return new Channel('EZManagementBatch');
    }

    public function broadcastAs(): string
    {
        return 'ez.management.batch';
    }
}