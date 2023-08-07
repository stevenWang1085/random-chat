<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Management\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class UserController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new UserService();
    }

    public function googleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleLogin()
    {
        try {
            DB::beginTransaction();
            #新增google user data
            $google_user = Socialite::driver('google')->stateless()->user();
            $filters = [
                'account' => $google_user->email,
                'password' => $google_user->email,
                'gender' => null,
                'username' => $google_user->name,
            ];
            $data = $this->service->register($filters, 'google');
            if (!$data) {
                $response = $this->responseMaker(601, $data);
            } else {
                $response = $this->responseMaker(102, $data);
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            $response = $this->responseMaker(500, $exception->getMessage());
        }

        return $response;
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
                $response = $this->responseMaker(603, null);
            } else {
                $response = $this->responseMaker(201, null);
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            $response = $this->responseMaker(500, $exception->getMessage());
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
                $response = $this->responseMaker(601, $data);
            } else {
                $response = $this->responseMaker(102, $data);
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $response = $this->responseMaker(500, $exception->getMessage());
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
            return $this->responseMaker(103, $return_data);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->responseMaker(500, $exception->getMessage());
        }
    }

    public function editProfile(UserRequest $request)
    {
        try {
            DB::beginTransaction();
            $filters = [
                'gender'  => $request->gender,
            ];
            $this->service->editProfile($filters);
            DB::commit();
            return $this->responseMaker(300, null);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            $response = $this->responseMaker(500, $exception->getMessage());
        }

        return $response;
    }
}
