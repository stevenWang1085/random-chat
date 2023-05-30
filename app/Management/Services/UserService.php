<?php

namespace App\Management\Services;

use App\Management\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class UserService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    public function register($filters)
    {
        $user = $this->repository->findUser($filters['account']);
        if (is_null($user)) {
            $filters['password'] = password_hash($filters['password'], PASSWORD_DEFAULT);
            return $this->repository->register($filters);
        } else {
            return false;
        }
    }

    public function login($filters)
    {
        $user = $this->repository->findUser($filters['account']);
        if ($user === null || password_verify($filters['password'], $user['password']) === false) return false;
        Auth::attempt($filters);
        Auth::login(Auth::user());

        return ['user_id' => Auth::id()];
    }
}
