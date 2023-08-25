<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class DashboardController
{
    public function list()
    {
        $user = Auth::user();
        if ($user['is_admin'] == 0) return ['code' => 403];
        $total_user = User::all()->count();
        $total_complete = Redis::hkeys("random_complete");
        $total_pending = Redis::hkeys("random_pending");

        return response()->json([
            'code' => 200,
            'user' => $total_user,
            'complete' => count($total_complete),
            'pending' => count($total_pending)
        ]);
    }
}
