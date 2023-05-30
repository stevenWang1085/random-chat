<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class storeRandomOnlineUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user_id;

    private $status;

    private $account;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_data)
    {
        $this->user_id = $user_data['user_id'];
        $this->account = $user_data['account'];
        $this->status = $user_data['status'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $now = Carbon::now();
        $cache_key = "random_online_user";
        $data = [
            'user_id'    => $this->user_id,
            'account'    => $this->account,
            'status'     => $this->status,
            'created_at' => $now->toDateTimeString()
        ];
        if (Cache::has($cache_key)) {
            $online_data = Cache::get($cache_key);
            array_push($online_data, $data);
            Cache::put($cache_key, $online_data);
        } else {
            Cache::put($cache_key, $data);
        }
        #執行配對指令
        Artisan::call('match:user');
    }
}
