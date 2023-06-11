<?php

namespace App\Management\Services;

use App\Management\Repositories\RoomRepository;
use App\Management\Repositories\UserFriendRepository;
use App\Management\Repositories\UserRoomRepository;

class UserFriendService
{
    private $repository;
    private $room_repository;
    private $user_room_repository;

    public function __construct()
    {
        $this->repository = new UserFriendRepository();
        $this->room_repository = new RoomRepository();
        $this->user_room_repository = new UserRoomRepository();
    }

    public function index($filters)
    {
        return $this->repository->getList($filters['user_id'], $filters['status']);
    }

    public function store($filters)
    {
        return $this->repository->store(
            $filters['from_user_id'],
            $filters['to_user_id'],
            $filters['status'],
            null
        );
    }

    public function update($filters)
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
