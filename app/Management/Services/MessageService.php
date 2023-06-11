<?php


namespace App\Management\Services;


use App\Events\RandomChatMessageEvent;
use App\Management\Repositories\MessageRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MessageService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new MessageRepository();
    }

    public function getRoomMessage($filters)
    {
        $chat_tag = Cache::tags(["room_{$filters['room_id']}"]);
        $chat_dates = Cache::get("room_{$filters['room_id']}_dates") ?? [];
        $chat_data = [];
        foreach ($chat_dates as $date) {
            if ($chat_tag->has($date)) {
                $chat_data[] = $chat_tag->get($date);
            } else {
                $chat_data[] = $this->repository->getRoomChatData($filters['room_id'], $date);
            }
        }
        if (count($chat_data) == 0) {
            $chat_data = $this->repository->getRoomChatData($filters['room_id'], null);
        }

        return $chat_data;
    }

    public function store($filters)
    {
        $now = Carbon::parse(now());
        $chat_date = $now->toDateString();
        $data = [
            'room_id'      => $filters['room_id'],
            'from_user_id' => $filters['from_user_id'],
            'to_user_id'   => $filters['to_user_id'],
            'message'      => $filters['message'],
            'created_at'   => $now->toDateTimeString(),
            'updated_at'   => $now->toDateTimeString()
        ];
        #儲存聊天日期
        $chat_dates_key = "room_{$filters['room_id']}_dates";
        if (Cache::has($chat_dates_key)) {
            $dates = Cache::get($chat_dates_key);
            if (! isset($dates[$chat_date])) {
                Cache::put($chat_dates_key, array_merge($dates, [$chat_date => $chat_date]));
            }
        } else {
            Cache::put($chat_dates_key, [$chat_date => $chat_date]);
        }
        #儲存聊天資料
        $chat_tag = Cache::tags(["room_{$filters['room_id']}"]);
        if ($chat_tag->has($chat_date)) {
            $chat_data = $chat_tag->get($chat_date);
            array_push($chat_data, $data);
            $chat_tag->put($chat_date, $chat_data);
        } else {
            $chat_tag->put($chat_date, [$data]);
        }
        #發送訊息至聊天室事件
        event(new RandomChatMessageEvent($data));

        return $data;
    }
}
