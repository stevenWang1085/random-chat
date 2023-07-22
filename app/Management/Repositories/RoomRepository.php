<?php

namespace App\Management\Repositories;

use App\Models\Room;

class RoomRepository
{
    private $model;

    public function __construct()
    {
        $this->model = new Room();
    }

    public function createRoom($data)
    {
        return $this->model::query()
            ->create($data);
    }

    public function deleteRoom($room_id)
    {
        return $this->model::query()
            ->where('id', $room_id)
            ->delete();
    }

    public function getAllRoomByType($type)
    {
        return $this->model::query()
            ->where('type', $type)
            ->get();
    }
}
