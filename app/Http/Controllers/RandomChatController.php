<?php

namespace App\Http\Controllers;

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

    public function startRandom()
    {
        $filters = [
            'user_id' => Auth::id(),
            'account' => Auth::user()['account'],
            'status'  => 'pending'
        ];
        try {
            $this->service->storeRandomOnlineUser($filters);
            $response = $this->responseMaker(203, null, null);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $response = $this->responseMaker(100, $exception->getMessage(), null);
        }

        return $response;
    }

}
