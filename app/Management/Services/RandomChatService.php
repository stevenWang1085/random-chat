<?php

namespace App\Management\Services;

use App\Events\LeaveRandomRoomEvent;
use App\Jobs\storeRandomOnlineUserJob;
use App\Management\Repositories\RoomRepository;
use App\Management\Repositories\UserRoomRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class RandomChatService
{
    private $online_random_user;

    private $user_room_repository;

    private $room_repository;

    public function __construct()
    {
        $this->online_random_user = Cache::get('random_online_user');
        $this->user_room_repository = new UserRoomRepository();
        $this->room_repository = new RoomRepository();
    }

    public function storeRandomOnlineUser($filters)
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
                Redis::hset('random_pending', 'user_id_'.$filters['user_id'], $data);
                return ['code' => 203];
            }
        } else {
            #配對中
            return ['code' => 204];
        }
    }

    private function processRandomUser($user_data)
    {
        $now = Carbon::now();
        $cache_key = "random_online_user";
        $data = [
            'user_id'       => $user_data['user_id'],
            'account'       => $user_data['account'],
            'status'        => $user_data['status'],
            'gender'        => $user_data['gender'],
            'select_gender' => $user_data['select_gender'],
            'created_at'    => $now->toDateTimeString()
        ];
        if (Cache::has($cache_key)) {
            $online_data = Cache::get($cache_key);
            array_push($online_data, $data);
            Cache::put($cache_key, $online_data);
        } else {
            Cache::put($cache_key, [$data]);
        }
    }

    public function checkRandomChat($filters)
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

    public function cancelRandom($filters)
    {
        Redis::hdel('random_pending', 'user_id_' . $filters['user_id']);
    }

    public function leaveRoom($filters)
    {
        $user_id = Auth::id();
        #清除cache
        Redis::del('random_complete', "user_id_{$filters['to_user_id']}");
        Redis::del('random_complete', "user_id_{$user_id}");
        Redis::del("random_room_id_{$filters['room_id']}");
        Redis::del("random_room_message_room_id_{$filters['room_id']}");
        #寄送離開事件
        event(new LeaveRandomRoomEvent($filters['to_user_id']));
    }
}
