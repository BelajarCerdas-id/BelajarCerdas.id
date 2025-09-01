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

class SchoolPartnerUserSubscription implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $schoolPartnerUserSubscription;

    public function __construct($schoolPartnerUserSubscription)
    {
        // kalau model tunggal, bungkus jadi collection (untuk broadcast tunggal dan berupa collection)
        $this->schoolPartnerUserSubscription = $schoolPartnerUserSubscription instanceof Collection
            ? $schoolPartnerUserSubscription
            : collect([$schoolPartnerUserSubscription]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): Channel
    {
        return new Channel('schoolPartnerUserSubscription');
    }

    public function broadcastAs(): string
    {
        return 'school.partner.user.subscription';
    }
}