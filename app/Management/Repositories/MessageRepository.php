<?php

namespace App\Management\Repositories;

use App\Models\Message;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class MessageRepository
{
    /**
     * @var Message
     */
    private $model;

    public function __construct()
    {
        $this->model = new Message();
    }

    /**
     * 取得聊天室資訊
     *
     * @param $room_id
     * @param null $date
     * @return Builder[]|Collection
     */
    public function getRoomChatData($room_id, $date = null)
    {
        return $this->model::query()
            ->where('room_id', $room_id)
            ->where(function ($q) use ($room_id, $date){
                if (! is_null($date)) {
                    $start_at = $date . ' 00:00:00';
                    $end_at = $date . ' 23:59:59';
                    $q->whereBetween('created_at', [$start_at, $end_at]);
                }
            })
            ->orderBy('created_at')
            ->get();
    }

    /**
     * 儲存訊息
     *
     * @param $data
     * @return bool
     */
    public function store($data)
    {
        return $this->model::query()
            ->insert($data);
    }
}
