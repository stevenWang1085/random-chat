<?php

namespace App\Management\Repositories;

use App\Models\UserFriend;

class UserFriendRepository
{
    private $model;

    public function __construct()
    {
        $this->model = new UserFriend();
    }

    public function getList($user_id, $status)
    {
        return $this->model::query()
            ->selectRaw('
                user_friends.id as user_friend_id,
                from_user_id,
                to_user_id,
                room_id,
                from_u.account as from_account,
                from_u.username as from_username,
                to_u.account as to_account,
                to_u.username as to_username,
                to_u.gender as to_gender,
                user_friends.created_at as send_at
            ')
            ->from('user_friends')
            ->join('users as from_u', 'from_u.id', '=', 'user_friends.from_user_id')
            ->join('users as to_u', 'to_u.id', '=', 'user_friends.to_user_id')
            ->where(function ($query) use ($user_id, $status){
                if ($status == 'pending') {
                    $query->where('to_user_id', $user_id);
                } else {
                    $query->where('from_user_id', $user_id);
                }
            })
            ->where('status', $status)
            ->get();
    }

    public function store($from_user_id, $to_user_id, $status, $room_id)
    {
        return $this->model::query()
            ->create([
                'from_user_id' => $from_user_id,
                'to_user_id'   => $to_user_id,
                'status'       => $status,
                'room_id'      => $room_id
            ]);
    }

    public function updateStatus($user_friend_id, $status)
    {
        return $this->model::query()
            ->updateOrCreate(
                [
                    'id' => $user_friend_id
                ],
                [
                    'status'  => $status,
                ]
            );

    }

    public function updateWhere($user_friend_id, $update_data)
    {
        return $this->model::query()
            ->where('id', $user_friend_id)
            ->update($update_data);
    }
}
