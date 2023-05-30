<?php

namespace App\Management\Repositories;

use App\Models\Message;

class MessageRepository
{
    private $model;

    public function __construct()
    {
        $this->model = new Message();
    }

    public function getRoomChatData($room_id, $date)
    {
        return $this->model::query()
            ->where('room_id', $room_id)
            ->where(function ($q) use ($room_id, $date){
                $q->where('room_id', $room_id);
                if (! is_null($date)) {
                    $start_at = $date . ' 00:00:00';
                    $end_at = $date . ' 23:59:59';
                    $q->whereBetween('created_at', [$start_at, $end_at]);
                }
            })
            ->orderBy('created_at')
            ->get();
    }
}
