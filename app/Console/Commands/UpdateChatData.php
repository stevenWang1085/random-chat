<?php

namespace App\Console\Commands;

use App\Management\Repositories\MessageRepository;
use App\Management\Repositories\RoomRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class UpdateChatData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:chat_data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update daily chat data.';

    const LIMIT_TIME = 10;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        #取得所有私人房間
        $room = new RoomRepository();
        $all_room = $room->getAllRoomByType('personal');
        #取得房間聊天內容(預設取十分鐘前)
        $now =  Carbon::now()->timezone('Asia/Taipei');
        $date = $now->toDateString();
        $start_time = $now->copy()->subMinutes(self::LIMIT_TIME)->startOfMinute()->toDateTimeString();
        $compare_time = Carbon::parse($start_time)->toDateString();
        if ($date != $compare_time) {
            $date = $compare_time;
        }
        $end_time = $now->copy()->subMinute()->endOfMinute()->toDateTimeString();
        $message_repository = new MessageRepository();
        foreach ($all_room as $value) {
            $message = Redis::lrange("personal_room_message_room_id_{$value['id']}_date_{$date}", 0, -1);
            $message = array_map(function ($node) {
                return json_decode($node, true);
            }, $message);
            if (count($message) != 0) {
                $all = collect($message)->whereBetween('created_at', [$start_time, $end_time])
                    ->values()->all();
                $message_repository->store($all);
            }
        }

        return Command::SUCCESS;
    }
}
