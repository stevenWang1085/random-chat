<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Management\Services\UserService;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new UserService();
    }

    public function register(UserRequest $request)
    {
        try {
            DB::beginTransaction();
            $filters = [
                'account' => $request->account,
                'password' => $request->password
            ];
            $this->service->register($filters);
            $response = $this->responseMaker(201, null, null);
            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            $response = $this->responseMaker(500, $exception->getMessage(), null);
        }

        return $response;
    }

    public function login(UserRequest $request)
    {
        try {

        } catch (\Exception $exception) {

        }
    }

    public function logout(UserRequest $request)
    {
        try {

        } catch (\Exception $exception) {

        }
    }
}
