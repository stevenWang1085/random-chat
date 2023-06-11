<?php

namespace App\Console\Commands;

use App\Events\SuccessMatchEvent;
use App\Management\Repositories\RoomRepository;
use App\Management\Repositories\UserRoomRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MatchRandomUserRoom extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'match:user {--gender=} {--select_gender=}';

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
        $room_repository = new RoomRepository();
        $user_room_repository = new UserRoomRepository();
        try {
            DB::beginTransaction();
            #取得線上隨機聊天使用者(等待中)
            $origin_online_user = collect(Cache::get('random_online_user'));
            $online_user = $origin_online_user->where('status', '=', 'pending');
//            if ($select_gender != 'all') {
//                $online_user = $online_user->where('gender', '=', $select_gender)
//                    ->where('select_gender', $gender);
//            }
            foreach ($online_user->chunk(2) as $value) {
                if ($value->count() != 2) break;
                #建立房間
                $room_data = [
                    'type' => 'random'
                ];
                $room = $room_repository->createRoom($room_data);
                $first_user = $value->first();
                $second_user = $value->last();
                $user_room_data = [
                    [
                        'room_id' => $room->id,
                        'user_id' => $first_user['user_id']
                    ],
                    [
                        'room_id' => $room->id,
                        'user_id' => $second_user['user_id']
                    ]
                ];
                $user_room_repository->insert($user_room_data);
                #寄送配對成功event
                $first_event_data = [
                    'user_id'            => $first_user['user_id'],
                    'match_user_id'      => $second_user['user_id'],
                    'match_user_account' => $second_user['account'],
                    'match_username'     => $second_user['username'],
                    'room_id'            => $room->id
                ];
                $second_event_data = [
                    'user_id'            => $second_user['user_id'],
                    'match_user_id'      => $first_user['user_id'],
                    'match_user_account' => $first_user['account'],
                    'match_username'     => $first_user['username'],
                    'room_id'            => $room->id
                ];
                event(new SuccessMatchEvent($first_event_data));
                event(new SuccessMatchEvent($second_event_data));
                #更新隨機配對線上清單
//                $update_value = $value->map(function ($node) {
//                    $node['status'] = 'chat';
//                    return $node;
//                });
//                Cache::put('random_online_user', $update_value->values()->all());
                Cache::forget('random_online_user');
            }
            DB::commit();
        } catch (\Exception $exception) {
            Log::error('Match User Command Error', [
                'line' => $exception->getLine(),
                'msg' => $exception->getMessage()
            ]);
            DB::rollBack();
        }
        return Command::SUCCESS;
    }
}
