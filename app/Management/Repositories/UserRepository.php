<?php

namespace App\Management\Repositories;

use App\Models\User;

class UserRepository
{
    private $model;

    public function __construct()
    {
        $this->model = new User();
    }

    public function register($filters)
    {
        return $this->model::query()
            ->firstOrCreate([
            'account'  => $filters['account'],
            'password' => $filters['password'],
            'gender'   => $filters['gender'],
            'username' => $filters['username'],
        ]);
    }

    public function findUser($account)
    {
        return $this->model::query()
            ->where('account', $account)
            ->first();
    }

}
