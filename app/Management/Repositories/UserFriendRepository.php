<?php

namespace App\Management\Repositories;

use App\Models\UserFriend;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserFriendRepository
{
    /**
     * @var UserFriend
     */
    private $model;

    public function __construct()
    {
        $this->model = new UserFriend();
    }

    /**
     * 取得好友清單
     *
     * @param $user_id
     * @param $status
     * @return Builder[]|Collection
     */
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
                user_friends.created_at as send_at,
                status
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

    /**
     * 發送好友申請
     *
     * @param $from_user_id
     * @param $to_user_id
     * @param $status
     * @param $room_id
     * @return Builder|Model
     */
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

    /**
     * 更新好友狀態
     *
     * @param $user_friend_id
     * @param $status
     * @return Builder|Model
     */
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

    /**
     * 更新好友資訊
     *
     * @param $user_friend_id
     * @param $update_data
     * @return int
     */
    public function updateWhere($user_friend_id, $update_data)
    {
        return $this->model::query()
            ->where('id', $user_friend_id)
            ->update($update_data);
    }

    /**
     * 取得好友資訊
     *
     * @param $from_user_id
     * @param $to_user_id
     * @return Builder|Model|object|null
     */
    public function getFriendStatus($from_user_id, $to_user_id)
    {
        return $this->model::query()
            ->where('from_user_id', $from_user_id)
            ->where('to_user_id', $to_user_id)
            ->first();
    }
}
