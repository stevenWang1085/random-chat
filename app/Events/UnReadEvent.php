<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class UnReadEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $type;

    public $user_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($type, $user_id)
    {
        $this->type = $type;
        $this->user_id = $user_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('personal-room-' . $this->user_id);
    }

    public function broadcastAs()
    {
        return "unread_{$this->type}_event";
    }

    public function broadcastWith()
    {
        return [
            'type'         => $this->type,
            'user_id'      => $this->user_id,
            'unread_count' => Redis::get("user_id_{$this->user_id}_unread_{$this->type}_count")
        ];
    }
}
