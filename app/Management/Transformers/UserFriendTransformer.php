<?php

namespace App\Management\Transformers;

use Illuminate\Support\Facades\Cache;

class UserFriendTransformer
{
    public function transformFriendList($data, $filters)
    {
        $data->transform(function ($node) {
            #取得最後聊天訊息
            $chat_tag = Cache::tags(["room_{$node['room_id']}"]);
            $chat_dates = Cache::get("room_{$node['room_id']}_dates") ?? [];
            $latest_date = end($chat_dates);
            $chat_data = $chat_tag->get($latest_date, []);
            $latest_message = end($chat_data);
            $latest_message['message'] = $latest_message['message'] ?? '';
            $node->latest_message = $latest_message['message'];
            $node->latest_send_at = $latest_message['created_at'] ?? '';
            return $node;
        });

        return $data->sortByDesc('latest_send_at')
            ->forPage($filters['page'], $filters['paginate'])
            ->values()
            ->all();
    }
}
