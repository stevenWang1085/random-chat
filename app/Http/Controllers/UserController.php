<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Management\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new UserService();
    }

    /**
     * 註冊
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserRequest $request)
    {
        try {
            DB::beginTransaction();
            $filters = [
                'account'  => $request->account,
                'password' => $request->password,
                'username' => $request->username,
                'gender'   => $request->gender
            ];
            $result = $this->service->register($filters);
            if (!$result) {
                $response = $this->responseMaker(603, null, null);
            } else {
                $response = $this->responseMaker(201, null, null);
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            $response = $this->responseMaker(500, $exception->getMessage(), null);
        }

        return $response;
    }

    /**
     * 登入
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(UserRequest $request)
    {
        try {
            $filters = [
                'account'  => $request->account,
                'password' => $request->password
            ];
            $data = $this->service->login($filters);
            if (!$data) {
                $response = $this->responseMaker(601, null, $data);
            } else {
                $response = $this->responseMaker(102, null, $data);
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $response = $this->responseMaker(500, $exception->getMessage(), null);
        }

        return $response;
    }

    /**
     * 登出
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(UserRequest $request)
    {
        try {
            $user_id = Auth::id();
            $return_data = ['user_id' => $user_id];
            Auth::logout();
            return $this->responseMaker(103, null, $return_data);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->responseMaker(500, $exception->getMessage(), null);
        }
    }
}
