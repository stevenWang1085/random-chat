<?php

namespace App\Console\Commands;

use App\Models\UserRoom;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class UpdatePersonalRoom extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:personal_room_user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data = UserRoom::all();
        $room = [];

        foreach ($data as $value) {
            if (isset($room[$value['room_id']])) {
                array_push($room[$value['room_id']], $value['user_id']);
            } else {
                $room[$value['room_id']][] = $value['user_id'];
            }
        }
        foreach ($room as $key => $value) {
            Redis::set("personal_room_id_{$key}", json_encode([
                'user_id' => $value
            ]));
        }

        return Command::SUCCESS;
    }
}
