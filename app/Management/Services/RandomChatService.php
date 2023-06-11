<?php

namespace App\Management\Services;

use App\Events\LeaveRandomRoomEvent;
use App\Jobs\storeRandomOnlineUserJob;
use App\Management\Repositories\RoomRepository;
use App\Management\Repositories\UserRoomRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

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
        if (Cache::has('random_online_user')) {
            $user_status = collect($this->online_random_user)->where([
                'status'  => 'pending',
                'user_id' => $filters['user_id']
            ])->values();
            if ($user_status->isEmpty()) {
                //$this->processRandomUser($filters);
                dispatch(new storeRandomOnlineUserJob($filters));
                return ['code' => 203];
            } else {
                return ['code' => 204];
            }
        } else {
            //$this->processRandomUser($filters);
            dispatch(new storeRandomOnlineUserJob($filters));
        }

        return ['code' => 203];
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
        $user_status = collect($this->online_random_user)
            ->where('status', '=', 'pending')
            ->where('user_id', $filters['user_id'])
            ->values();
        if (! $user_status->isEmpty()) {
            #配對中
            return ['code' => 204, 'data' => [
                'room_id' => null
            ]];
        }
        #檢查是否已有隨機聊天室
        $random_room = $this->user_room_repository->getUserRoomFromType($filters['user_id'], 'random');
        if (! is_null($random_room)) {
            $room_id = $random_room->relationRoom->id;
            $users = $this->user_room_repository->getRoomFromRoomId($room_id);
            $match_user = $users->where('user_id', '!=', $filters['user_id'])->first();

            return ['code' => 205, 'data' => [
                'room_id'      => $room_id,
                'to_user_id'   => $match_user->user_id,
                'to_user_name' => $match_user->relationUser->username
            ]];
        }

        return ['code' => 206, 'data' => [
            'room_id' => null
        ]];
    }

    public function leaveRoom($filters)
    {
        #清除cache
        Cache::tags(["room_{$filters['room_id']}"])->flush();
        #清除db資料
        $this->room_repository->deleteRoom($filters['room_id']);
        #寄送離開事件
        event(new LeaveRandomRoomEvent($filters['to_user_id']));
    }
}
