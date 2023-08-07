<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RandomChatMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room_id;

    public $from_user_id;

    public $to_user_id;

    public $message;

    public $created_at;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message_data)
    {
        $this->room_id = $message_data['room_id'];
        $this->from_user_id = $message_data['from_user_id'];
        $this->to_user_id = $message_data['to_user_id'];
        $this->message = $message_data['message'];
        $this->created_at = $message_data['created_at'];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('random-chat-room-' . $this->room_id);
    }

    public function broadcastAs()
    {
        return 'random-chat-room';
    }

    public function broadcastWith()
    {
        return [
            'created_at'   => $this->created_at,
            'from_user_id' => $this->from_user_id,
            'to_user_id'   => $this->to_user_id,
            'message'      => $this->message
        ];
    }
}
