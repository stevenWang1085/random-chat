<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use App\Management\Services\MessageService;
use App\Management\Transformers\MessageTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    private $service;
    private $transformer;

    public function __construct()
    {
        $this->service = new MessageService();
        $this->transformer = new MessageTransformer();
    }

    /**
     * 取得房間聊天訊息
     *
     * @param MessageRequest $request
     * @return JsonResponse
     */
    public function getRoomMessage(MessageRequest $request)
    {
        $filters = [
            'room_id' => $request->room_id
        ];
        try {
            $data = $this->service->getRoomMessage($filters);
            $result = $this->transformer->transformMessage($data);
            $response = $this->responseMaker(100, $result);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $response = $this->responseMaker(500, $exception->getMessage());
        }

        return $response;
    }

    /**
     * 儲存聊天訊息
     *
     * @param MessageRequest $request
     * @return JsonResponse
     */
    public function store(MessageRequest $request)
    {
        $filters = [
            'room_id'      => $request->room_id,
            'from_user_id' => $request->from_user_id,
            'to_user_id'   => $request->to_user_id,
            'message'      => $request->message
        ];
        try {
            $data = $this->service->store($filters);
            $response = $this->responseMaker(202, $data);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $response = $this->responseMaker(500, $exception->getMessage());
        }

        return $response;
    }
}
