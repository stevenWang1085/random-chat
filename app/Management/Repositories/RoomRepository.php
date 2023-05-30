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
}
