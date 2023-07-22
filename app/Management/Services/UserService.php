<?php

namespace App\Management\Services;

use App\Management\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    public function register($filters, $type = null)
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

    public function googleLogin($user, $filters)
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

    public function login($filters)
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

    public function editProfile($filters)
    {
        $update_data = $filters;
        $this->repository->editProfile(Auth::id(), $update_data);
    }

}
