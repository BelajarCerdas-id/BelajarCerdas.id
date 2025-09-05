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

class BankSoalEnglishZoneEditQuestion implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public $bankSoalEditQuestion;
    public function __construct(Collection $bankSoalEditQuestion)
    {
        // jika yang di broadcast adalah sebuah collection
        $this->bankSoalEditQuestion = $bankSoalEditQuestion->pluck('level');
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