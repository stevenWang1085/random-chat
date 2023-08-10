<?php

namespace App\Management\Services;

use App\Management\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService
{
    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    /**
     * 註冊
     *
     * @param array $filters
     * @param null $type
     * @return array|false|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function register(array $filters, $type = null)
    {
        $user = $this->repository->findUser($filters['account']);
        if ($type == 'google') return $this->googleLogin($user, $filters);
        if (is_null($user)) {
            $filters['password'] = password_hash($filters['password'], PASSWORD_DEFAULT);
            return $this->repository->register($filters);
        } else {
            return false;
        }
    }

    /**
     * google 登入
     *
     * @param $user
     * @param array $filters
     * @return array
     */
    public function googleLogin($user, array $filters)
    {
        if (is_null($user)) {
            $filters['password'] = password_hash($filters['password'], PASSWORD_DEFAULT);
            $user = $this->repository->register($filters);
        }
        $token = JWTAuth::attempt([
            'account' => $filters['account'],
            'password' => $filters['password'],
        ]);

        Auth::login(JWTAuth::user());

        return [
            'user_id'                 => Auth::id(),
            'username'                => $user['username'],
            'gender'                  => $user['gender'],
            'token'                   => $token,
            'add_friend_unread_count' => Redis::get("user_id_{$user['id']}_unread_add_friend_count"),
        ];
    }

    /**
     * 一般登入
     *
     * @param array $filters
     * @return array|false
     */
    public function login(array $filters)
    {
        $user = $this->repository->findUser($filters['account']);
        if ($user === null || password_verify($filters['password'], $user['password']) === false) return false;
        $token = JWTAuth::attempt($filters);
        Auth::login(JWTAuth::user());

        return [
            'user_id'                 => Auth::id(),
            'username'                => $user['username'],
            'token'                   => $token,
            'add_friend_unread_count' => Redis::get("user_id_{$user['id']}_unread_add_friend_count")
        ];
    }

    /**
     * 編輯基本資料
     *
     * @param array $filters
     */
    public function editProfile(array $filters)
    {
        $update_data = $filters;
        $this->repository->editProfile(Auth::id(), $update_data);
    }

}
