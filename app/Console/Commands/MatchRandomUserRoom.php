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
    protected $signature = 'match:user';

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
            $online_user = collect(Cache::get('random_online_user'));
            $online_user = $online_user->where('status', '=', 'pending');
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
                        'user_id' => $first_user->user_id
                    ],
                    [
                        'room_id' => $room->id,
                        'user_id' => $second_user->user_id
                    ]
                ];
                $user_room_repository->insert($user_room_data);
                #寄送配對成功event
                $first_event_data = [
                    'user_id'            => $first_user->id,
                    'match_user_id'      => $second_user->user_id,
                    'match_user_account' => $second_user->account,
                    'room_id'            => $room->id
                ];
                $second_event_data = [
                    'user_id'            => $second_user->id,
                    'match_user_id'      => $first_user->user_id,
                    'match_user_account' => $first_user->account,
                    'room_id'            => $room->id
                ];
                event(new SuccessMatchEvent($first_event_data));
                event(new SuccessMatchEvent($second_event_data));
                #更新隨機配對線上清單
                $first_user->status = 'chat';
                $second_user->status = 'chat';
                DB::commit();
            }

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();
        }

        return Command::SUCCESS;
    }
}
