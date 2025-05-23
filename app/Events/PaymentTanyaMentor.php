<?php

namespace App\Events;

use App\Models\TanyaMentorPayments;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentTanyaMentor implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $paymentTanyaMentor;

    public function __construct(TanyaMentorPayments $paymentTanyaMentor)
    {
        $this->paymentTanyaMentor = $paymentTanyaMentor;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): Channel
    {
        return new Channel('paymentTanyaMentor');
    }

    public function broadcastAs(): string
    {
        return 'payment.tanya.mentor';
    }
}