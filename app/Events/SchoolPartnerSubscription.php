<?php

namespace App\Events;

use App\Models\SchoolPartner;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SchoolPartnerSubscription implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $schoolPartnerSubscription;

    public function __construct(SchoolPartner $schoolPartnerSubscription)
    {
        $this->schoolPartnerSubscription = $schoolPartnerSubscription;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): Channel
    {
        return new Channel('schoolPartnerSubscription');
    }

    public function broadcastAs(): string
    {
        return 'school.partner.subscription';
    }
}