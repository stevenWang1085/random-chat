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
}
