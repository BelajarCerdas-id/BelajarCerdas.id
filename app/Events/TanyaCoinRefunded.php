<?php

namespace App\Events;

use App\Models\TanyaUserCoin;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TanyaCoinRefunded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $tanyaKoinRefund;
    public function __construct(TanyaUserCoin $tanyaKoinRefund)
    {
        $this->tanyaKoinRefund = $tanyaKoinRefund;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): Channel
    {
        return new Channel('tanyaUserKoin');
    }

    public function broadcastAs(): string
    {
        return 'tanya.coin.refunded';
    }
}