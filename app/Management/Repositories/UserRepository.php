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

    public function register($account, $password)
    {
        return $this->model::query()->firstOrCreate([
            'account'  => $account,
            'password' => $password
        ]);
    }

}
