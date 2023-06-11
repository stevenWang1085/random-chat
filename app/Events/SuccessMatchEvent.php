<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SuccessMatchEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $user_id;

    private $match_user_id;

    private $match_user_account;

    private $match_username;

    private $room_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->user_id = $data['user_id'];
        $this->match_user_id = $data['match_user_id'];
        $this->match_user_account = $data['match_user_account'];
        $this->match_username = $data['match_username'];
        $this->room_id = $data['room_id'];
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
        return 'success-get-random-user';
    }

    public function broadcastWith()
    {
        return [
            'user_id'            => $this->user_id,
            'room_id'            => $this->room_id,
            'match_user_id'      => $this->match_user_id,
            'match_user_account' => $this->match_user_account,
            'match_username'     => $this->match_username,
        ];
    }
}
