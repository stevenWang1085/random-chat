<?php

namespace App\Management\Repositories;

use App\Models\UserRoom;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class UserRoomRepository
{
    /**
     * @var UserRoom
     */
    private $model;

    public function __construct()
    {
        $this->model = new UserRoom();
    }

    /**
     * 新增使用者與房間資訊
     *
     * @param $data
     * @return bool
     */
    public function insert($data)
    {
        return $this->model::query()
            ->insert($data);
    }

    /**
     * 透過房間編號取得房間與使用者資訊
     *
     * @param $room_id
     * @return Builder[]|Collection
     */
    public function getRoomFromRoomId($room_id)
    {
        return $this->model::query()
            ->with('relationUser')
            ->where('room_id', $room_id)
            ->get();
    }
}
