<?php

namespace App\Management\Services;

use App\Events\LeaveRandomRoomEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class RandomChatService
{
    public function __construct()
    {
    }

    /**
     * 儲存隨機配對使用者狀態
     *
     * @param array $filters
     * @return int[]
     */
    public function storeRandomOnlineUser(array $filters)
    {
        #檢查是否已經排隊
        if (is_null(Redis::hget('random_pending', 'user_id_'.$filters['user_id']))) {
            #檢查使否已配對
            if (! is_null(Redis::hget('random_complete', 'user_id_'.$filters['user_id']))) {
                #已配對
                return ['code' => 205];
            } else {
                #未配對
                $filters['created_at'] =  Carbon::now()->toDateTimeString();
                $data = json_encode($filters);
                #塞入配對等待區
                Redis::hset('random_pending', 'user_id_'.$filters['user_id'], $data);
                return ['code' => 203];
            }
        } else {
            #配對中
            return ['code' => 204];
        }
    }

    /**
     * 檢查隨機聊天狀態
     *
     * @param array $filters
     * @return array
     */
    public function checkRandomChat(array $filters)
    {
        #檢查是否已在配對
        if (is_null(Redis::hget('random_pending', 'user_id_'.$filters['user_id']))) {
            #檢查使否已配對
            if (! is_null(Redis::hget('random_complete', 'user_id_'.$filters['user_id']))) {
                #已配對，取得資訊
                $match_data = Redis::hget('random_complete', 'user_id_'.$filters['user_id']);
                $match_data = json_decode($match_data, true);
                return ['code' => 205, 'data' => [
                    'room_id'      => $match_data['room_id'],
                    'to_user_id'   => $match_data['match_user_id'],
                    'to_user_name' => $match_data['match_username']
                ]];
            } else {
                #未配對
                return ['code' => 206, 'data' => ['room_id' => null]];
            }
        } else {
            #配對中
            return ['code' => 204, 'data' => ['room_id' => null]];
        }
    }

    /**
     * 取消等待隨機配對
     *
     * @param array $filters
     */
    public function cancelRandom(array $filters)
    {
        #從配對等待區移除
        Redis::hdel('random_pending', 'user_id_' . $filters['user_id']);
    }

    /**
     * 離開配對聊天室
     *
     * @param array $filters
     */
    public function leaveRoom(array $filters)
    {
        $user_id = Auth::id();
        #清除配對資訊，與聊天紀錄
        Redis::del('random_complete', "user_id_{$filters['to_user_id']}");
        Redis::del('random_complete', "user_id_{$user_id}");
        Redis::del("random_room_id_{$filters['room_id']}");
        Redis::del("random_room_message_room_id_{$filters['room_id']}");
        #寄送離開事件
        event(new LeaveRandomRoomEvent($filters['to_user_id']));
    }
}
