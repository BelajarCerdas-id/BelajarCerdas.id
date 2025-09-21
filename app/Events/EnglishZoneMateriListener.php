<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EnglishZoneMateriListener implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

     // kalo pada saat pusher listener ada function yang delete, maka gunakan ini jika CRUD menggunakan satu listener
    public $tipe_model; // db EnglishZoneMateri
    public $action; // macam" action CRUD EnglishZoneMateri (create, update, delete)
    public $data; // isi data setap model (db)
    public function __construct($tipe_model, $action, $data)
    {
        $this->tipe_model = $tipe_model;
        $this->action = $action;
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): Channel
    {
        return new Channel('managementMateri');
    }

    public function broadcastAs(): string
    {
        return 'management.materi';
    }
}