<?php

namespace App\Management\Services;

use App\Jobs\storeRandomOnlineUserJob;
use Illuminate\Support\Facades\Cache;

class RandomChatService
{
    public function __construct()
    {

    }

    public function storeRandomOnlineUser($filters)
    {
        #檢查是否已經排隊
        if (Cache::has('random_online_user')) {
            $online_data = Cache::get('random_online_user');
            $user_status = collect($online_data)->where([
                'status'  => 'pending',
                'user_id' => $filters['user_id']
            ])->values();
            if ($user_status->isEmpty()) {
                dispatch(new storeRandomOnlineUserJob($filters));
            }
        } else {
            dispatch(new storeRandomOnlineUserJob($filters));
        }
    }
}
