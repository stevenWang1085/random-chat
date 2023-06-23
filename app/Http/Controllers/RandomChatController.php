<?php

namespace App\Http\Controllers;

use App\Http\Requests\RandomChatRequest;
use App\Management\Services\RandomChatService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RandomChatController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new RandomChatService();
    }

    public function startRandom(RandomChatRequest $request)
    {
        $user = Auth::user();

        $filters = [
            'user_id'       => $user['id'],
            'account'       => $user['account'],
            'status'        => 'pending',
            'gender'        => $user['gender'],
            'username'      => $user['username'],
            'select_gender' => $request->select_gender,
        ];
        try {
            $result = $this->service->storeRandomOnlineUser($filters);
            $response = $this->responseMaker($result['code'], null);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $response = $this->responseMaker(500, $exception->getMessage());
        }

        return $response;
    }

    public function cancelRandom()
    {
        $filters = [
            'user_id' => Auth::id()
        ];
        try {
            $this->service->cancelRandom($filters);
            $response = $this->responseMaker(402, null);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $response = $this->responseMaker(500, $exception->getMessage());
        }

        return $response;
    }

    public function checkRandomChat()
    {
        $filters = [
            'user_id'   => Auth::id(),
            'room_type' => 'random'
        ];

        try {
            $result = $this->service->checkRandomChat($filters);
            $response = $this->responseMaker($result['code'], $result['data']);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $response = $this->responseMaker(500, $exception->getMessage());
        }

        return $response;
    }

    public function leaveRandomRoom(RandomChatRequest $request)
    {
        $filters = [
            'to_user_id' => $request->to_user_id,
            'room_id'    => $request->room_id
        ];
        try {
            $this->service->leaveRoom($filters);
            $response = $this->responseMaker(401, null);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $response = $this->responseMaker(500, $exception->getMessage());
        }

        return $response;
    }

}
