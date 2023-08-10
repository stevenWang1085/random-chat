<?php

namespace App\Management\Repositories;

use App\Models\User;

class UserRepository
{
    /**
     * @var User
     */
    private User $model;

    public function __construct()
    {
        $this->model = new User();
    }

    /**
     * 註冊
     *
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function register(array $filters)
    {
        return $this->model::query()
            ->firstOrCreate([
            'account'  => $filters['account'],
            'password' => $filters['password'],
            'gender'   => $filters['gender'],
            'username' => $filters['username'],
        ]);
    }

    /**
     * 透過帳號搜尋使用者
     *
     * @param string $account
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function findUser(string $account)
    {
        return $this->model::query()
            ->where('account', $account)
            ->first();
    }

    /**
     * 編輯使用者資料
     *
     * @param $user_id
     * @param $update_data
     * @return int
     */
    public function editProfile($user_id, $update_data)
    {
        return $this->model::query()
            ->where('id', $user_id)
            ->update($update_data);
    }

}
