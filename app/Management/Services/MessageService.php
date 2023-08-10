<?php


namespace App\Management\Services;


use App\Events\RandomChatMessageEvent;
use App\Management\Repositories\MessageRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class MessageService
{
    /**
     * @var MessageRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new MessageRepository();
    }

    /**
     * 取得聊天房間訊息
     *
     * @param array $filters
     * @return array|Builder[]|Collection|\Illuminate\Support\Collection
     */
    public function getRoomMessage(array $filters)
    {
        if ($filters['room_type'] == 'random') {
            #隨機聊天室
            $chat_data = Redis::lrange("random_room_message_room_id_{$filters['room_id']}", 0, -1);
            $chat_data = collect($chat_data)->transform(function ($node) {
                return json_decode($node);
            });
        } else {
            #私人聊天室
            #取得聊天日期
            $chat_data = [];
            $personal_room_key = "personal_room_id_{$filters['room_id']}_dates";
            if (Redis::exists($personal_room_key) == 0) {
                #search db
                $chat_data = $this->repository->getRoomChatData($filters['room_id']);
                $chat_data = $chat_data->transform(function ($node) {
                    $node->chat_date = Carbon::parse($node['created_at'])->toDateString();
                    return $node;
                });
                $chat_data = $chat_data->groupBy('chat_date');
            } else {
                $all_dates = Redis::sMembers($personal_room_key);
                foreach ($all_dates as $value) {
                    $message = Redis::lrange("personal_room_message_room_id_{$filters['room_id']}_date_{$value}", 0, -1);
                    $message = collect($message)->transform(function ($node) {
                        return json_decode($node);
                    });
                    $chat_data[$value] = $message;
                }
            }
        }

        return $chat_data;
    }

    /**
     * 儲存聊天訊息
     *
     * @param array $filters
     * @return array
     */
    public function store(array $filters)
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
        if ($filters['room_type'] == 'random') {
            #隨機聊天
            #檢查room_id
            if (Redis::exists("random_room_id_{$filters['room_id']}")) {
                #確認為正確使用者
                $room_users = json_decode(Redis::get("random_room_id_{$filters['room_id']}"));
                if (count(array_diff($room_users->user_id, [$data['from_user_id'], $data['to_user_id']])) != 0) {
                    #房間用戶錯誤
                    return ['code' => 605, 'data' => null];
                } else {
                    Redis::rpush("random_room_message_room_id_{$filters['room_id']}", json_encode($data));
                }
            } else {
                #房間不存在
                return ['code' => 606, 'data' => null];
            }
        } else {
            #romm_type = personal(私人聊天室)
            #儲存聊天日期
            $personal_room_key = "personal_room_id_{$filters['room_id']}_dates";
            Redis::sadd($personal_room_key, $chat_date);
            Redis::rpush("personal_room_message_room_id_{$filters['room_id']}_date_{$chat_date}", json_encode($data));
        }
        #發送訊息至聊天室事件
        event(new RandomChatMessageEvent($data));

        return ['code' => 202, 'data' => $data];
    }
}
