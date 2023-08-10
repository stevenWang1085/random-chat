<?php

namespace App\Management\Repositories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class RoomRepository
{
    /**
     * @var Room
     */
    private $model;

    public function __construct()
    {
        $this->model = new Room();
    }

    /**
     * 新增房間
     *
     * @param array $data
     * @return Builder|Model
     */
    public function createRoom(array $data)
    {
        return $this->model::query()
            ->create($data);
    }

    /**
     * 刪除房間
     *
     * @param $room_id
     * @return mixed
     */
    public function deleteRoom($room_id)
    {
        return $this->model::query()
            ->where('id', $room_id)
            ->delete();
    }

    /**
     * 透過類型取得房間
     *
     * @param string $type
     * @return Builder[]|Collection
     */
    public function getAllRoomByType(string $type)
    {
        return $this->model::query()
            ->where('type', $type)
            ->get();
    }
}
