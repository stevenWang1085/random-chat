<?php

namespace App\Http\Controllers;

use App\Http\Requests\RandomChatRequest;
use App\Management\Services\RandomChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RandomChatController extends Controller
{
    /**
     * @var RandomChatService
     */
    private $service;

    public function __construct()
    {
        $this->service = new RandomChatService();
    }

    /**
     * 開始隨機配對
     *
     * @param RandomChatRequest $request
     * @return JsonResponse
     */
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

    /**
     * 取消等待隨機配對
     *
     * @return JsonResponse
     */
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

    /**
     * 檢查隨機聊天狀態
     *
     * @return JsonResponse
     */
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

    /**
     * 離開隨機配對聊天室
     *
     * @param RandomChatRequest $request
     * @return JsonResponse
     */
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
