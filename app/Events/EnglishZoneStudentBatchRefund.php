<?php

namespace App\Events;

use App\Models\FeatureSubscriptionHistory;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EnglishZoneStudentBatchRefund implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public $EZStudentBatchRefund;
    public function __construct(FeatureSubscriptionHistory $EZStudentBatchRefund)
    {
        // jika yang di broadcast adalah sebuah collection
        $this->EZStudentBatchRefund = $EZStudentBatchRefund;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): Channel
    {
        return new Channel('studentBatchRefund');
    }

    public function broadcastAs(): string
    {
        return 'student.batch.refund';
    }
}