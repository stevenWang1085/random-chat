<?php

namespace App\Management\Transformers;

use App\Events\UnReadEvent;
use Illuminate\Support\Facades\Redis;

class UserFriendTransformer
{
    /**
     * 轉換好友清單資訊
     *
     * @param $data
     * @param $filters
     * @return mixed
     */
    public function transformFriendList($data, $filters)
    {
        $data->transform(function ($node) {
            #取得最後聊天訊息
            if ($node->status == 'confirm') {
                $all_dates = Redis::sMembers("personal_room_id_{$node['room_id']}_dates");
                if (count($all_dates) != 0) {
                    $latest_date = max($all_dates);
                    $chat_data = Redis::lrange("personal_room_message_room_id_{$node['room_id']}_date_{$latest_date}", 0, -1);
                    $latest_message = json_decode(end($chat_data));
                    $node->latest_message = $latest_message->message ?? '';
                    $node->latest_send_at = $latest_message->created_at ?? '';
                    return $node;
                } else {
                    $node->latest_message = '';
                    $node->latest_send_at = '';
                }
            }
            return $node;
        });
        #點擊審核列表，清空未讀訊息
        if ($filters['status'] == 'pending') {
            Redis::set("user_id_{$filters['user_id']}_unread_add_friend_count", 0);
            event(new UnReadEvent('add_friend', $filters['user_id']));
        }

        return $data->sortByDesc('latest_send_at')
            ->forPage($filters['page'], $filters['paginate'])
            ->values()
            ->all();
    }
}
