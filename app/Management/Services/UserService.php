<?php

namespace App\Management\Services;

use App\Management\Repositories\UserRepository;

class UserService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    public function register($filters)
    {
        $password = password_hash($filters['password'], PASSWORD_DEFAULT);

        return $this->repository->register($filters['account'], $password);
    }
}
