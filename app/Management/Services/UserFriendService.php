<?php

namespace App\Management\Services;

use App\Events\UnReadEvent;
use App\Management\Repositories\RoomRepository;
use App\Management\Repositories\UserFriendRepository;
use App\Management\Repositories\UserRoomRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Redis;

class UserFriendService
{
    /**
     * @var UserFriendRepository
     */
    private $repository;

    /**
     * @var RoomRepository
     */
    private $room_repository;

    /**
     * @var UserRoomRepository
     */
    private $user_room_repository;

    public function __construct()
    {
        $this->repository = new UserFriendRepository();
        $this->room_repository = new RoomRepository();
        $this->user_room_repository = new UserRoomRepository();
    }

    /**
     * 取得好友清單
     *
     * @param array $filters
     * @return Builder[]|Collection
     */
    public function index(array $filters)
    {
        return $this->repository->getList($filters['user_id'], $filters['status']);
    }

    /**
     * 發送好友申請
     *
     * @param array $filters
     * @return int[]
     */
    public function store(array $filters)
    {
        #檢查是否已經送過或早已是好友
        $check_from_friend = $this->repository->getFriendStatus($filters['from_user_id'], $filters['to_user_id']);
        $check_to_friend = $this->repository->getFriendStatus($filters['to_user_id'], $filters['from_user_id']);

        if (! is_null($check_from_friend)) {
            if ($check_from_friend['status'] == 'confirm') {
                return  ['code' => 701];
            } elseif ($check_from_friend['status'] == 'reject') {
                return  ['code' => 702];
            } else {
                return  ['code' => 704];
            }
        } else {
            if (! is_null($check_to_friend)) {
                if ($check_to_friend['status'] == 'reject') {
                    return  ['code' => 705];
                } else {
                    return  ['code' => 703];
                }
            } else {
                #pending
                #送出提醒事件
                Redis::incr("user_id_{$filters['to_user_id']}_unread_add_friend_count");
                event(new UnReadEvent('add_friend', $filters['to_user_id']));
                $this->repository->store(
                    $filters['from_user_id'],
                    $filters['to_user_id'],
                    $filters['status'],
                    null
                );
                return ['code' => 207];
            }
        }
    }

    /**
     * 更新好友狀態
     *
     * @param array $filters
     */
    public function update(array $filters)
    {
        #更新好友狀態
        $result = $this->repository->updateStatus($filters['user_friend_id'], $filters['status']);
        if ($filters['status'] == 'confirm') {
            #新增私人聊天房間
            $room_data = [
                'type' => 'personal'
            ];
            $room = $this->room_repository->createRoom($room_data);
            $user_room_data = [
                [
                    'room_id' => $room->id,
                    'user_id' => $result->to_user_id
                ],
                [
                    'room_id' => $room->id,
                    'user_id' => $result->from_user_id
                ]
            ];
            $this->user_room_repository->insert($user_room_data);
            $this->repository->store(
                $result->to_user_id,
                $result->from_user_id,
                'confirm',
                $room->id
            );
            $this->repository->updateWhere($filters['user_friend_id'], [
                'room_id' => $room->id
            ]);
        }
    }
}
