<?php

namespace App\Console\Commands;

use App\Events\SuccessMatchEvent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class MatchRandomUserRoomV2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'match:user_v2';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'match random online user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            #取得當前待配對清單
            $all_user = Redis::hgetall('random_pending');

            #比較清單
            $compare_users = collect($all_user)->transform(function ($node) {
                return json_decode($node);
            });
            $continue_data = [];
            $continue_user_id = [];
            #處理配對
            foreach ($all_user as $user_id => $value)  {
                if (count($continue_data) != 0) {
                    $continue_user_id = array_values($continue_data);
                    if (in_array($user_id, array_keys($continue_data))) {
                        continue;
                    }
                }
                $data = json_decode($value, true);
                if ($data['select_gender'] != 'all') {
                    $match_user = $compare_users
                        ->where('user_id', '!=', $data['user_id'])
                        ->whereNotIn('user_id', $continue_user_id)
                        ->where('gender', $data['select_gender'])
                        ->whereIn('select_gender', ['all', $data['gender']])
                        ->sortBy('created_at')
                        ->first();
                } else {
                    $match_user = $compare_users
                        ->where('user_id', '!=', $data['user_id'])
                        ->whereNotIn('user_id', $continue_user_id)
                        ->whereIn('select_gender', [$data['gender'], 'all'])
                        ->sortBy('created_at')
                        ->first();
                }
                #配對成功
                if (! is_null($match_user)) {
                    #建立房間
                    if (Redis::exists('current_random_room_id') == 0) {
                        Redis::set('current_random_room_id', 1);
                    } else {
                        Redis::incr('current_random_room_id');
                    }
                    $room_id = Redis::get('current_random_room_id');
                    #建立配對資訊
                    $first_event_data = [
                        'user_id'            => $data['user_id'],
                        'match_user_id'      => $match_user->user_id,
                        'match_user_account' => $match_user->account,
                        'match_username'     => $match_user->username,
                        'room_id'            => $room_id
                    ];
                    $second_event_data = [
                        'user_id'            => $match_user->user_id,
                        'match_user_id'      => $data['user_id'],
                        'match_user_account' => $data['account'],
                        'match_username'     => $data['username'],
                        'room_id'            => $room_id
                    ];
                    #更改配對狀態
                    Redis::hdel('random_pending', 'user_id_' . $data['user_id']);
                    Redis::hdel('random_pending', 'user_id_' . $match_user->user_id);
                    Redis::hset('random_complete', 'user_id_' . $data['user_id'], json_encode($first_event_data));
                    Redis::hset('random_complete', 'user_id_' . $match_user->user_id, json_encode($second_event_data));
                    #紀錄random room user
                    Redis::set('random_room_id_' . $room_id, json_encode([
                        'user_id' => [$data['user_id'], $match_user->user_id]
                    ]));
                    #寄送配對成功event
                    event(new SuccessMatchEvent($first_event_data));
                    event(new SuccessMatchEvent($second_event_data));
                    $continue_data = array_merge($continue_data, [$user_id => $data['user_id']]);
                    $continue_data = array_merge($continue_data, ['user_id_' . $match_user->user_id => $match_user->user_id]);
                }
            }
        } catch (\Exception $exception) {
            Log::error('Match User Command Error', [
                'line' => $exception->getLine(),
                'msg' => $exception->getMessage()
            ]);
        }
        return Command::SUCCESS;
    }
}
