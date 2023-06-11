<?php

namespace App\Management\Repositories;

use App\Models\UserRoom;

class UserRoomRepository
{
    private $model;

    public function __construct()
    {
        $this->model = new UserRoom();
    }

    public function insert($data)
    {
        return $this->model::query()
            ->insert($data);
    }

    public function getUserRoomFromType($user_id, $room_type)
    {
        return $this->model::query()
            ->with('relationRoom')
            ->whereHas('relationRoom', function ($q) use ($room_type){
                $q->where('type', $room_type);
            })
            ->where('user_id', $user_id)
            ->first();
    }

    public function getRoomFromRoomId($room_id)
    {
        return $this->model::query()
            ->with('relationUser')
            ->where('room_id', $room_id)
            ->get();
    }
}
