<?php

namespace App\Events;

use App\Models\SoalPembahasanQuestions;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BankSoalListener implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public $bankSoal;
    public function __construct(SoalPembahasanQuestions $bankSoal)
    {
        // jika yang di broadcast adalah sebuah collection
        $this->bankSoal = $bankSoal;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): Channel
    {
        return new Channel('bankSoal');
    }

    public function broadcastAs(): string
    {
        return 'bank.soal';
    }
}