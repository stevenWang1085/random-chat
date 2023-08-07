<?php

namespace Tests\Feature;

use App\Events\RandomChatMessageEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Redis::flushdb();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testStore()
    {
        Event::fake();
        #隨機聊天訊息
        $match_users = $this->setRandomMatchUser();
        $room_id = Redis::get('current_random_room_id');
        $request = [
            'room_id'      => $room_id,
            'from_user_id' => $match_users['user_2_id'],
            'to_user_id'   => $match_users['user_1_id'],
            'message'      => 'hello',
            'room_type'    => 'random'
        ];
        $response = $this->post('api/v1/message/send', $request);
        Event::assertDispatched(RandomChatMessageEvent::class, function ($e) use ($request){
            return $e->message === $request['message'];
        });
        $message_exists_status = Redis::exists("random_room_message_room_id_{$room_id}");
        $message_array = Redis::lrange("random_room_message_room_id_{$room_id}", 0, 1);
        $message_detail = json_decode($message_array[0], true);
        self::assertEquals(1, $message_exists_status);
        self::assertEquals('hello', $message_detail['message']);
        $response
            ->assertStatus(200)
            ->assertJsonPath('code', '1202');
    }

    public function testGetRoomMessage()
    {
        $match_users = $this->setRandomMatchUser();
        $room_id = Redis::get('current_random_room_id');
        $test_messages = ['test1', 'test2', 'test3'];
        foreach ($test_messages as $message) {
            $this->sendMessage($match_users, 'random', $message, $room_id);
        }
        $message_array = Redis::lrange("random_room_message_room_id_{$room_id}", 0, -1);
        foreach ($message_array as $key => $message) {
            $message_detail = json_decode($message, true);
            self::assertEquals($test_messages[$key], $message_detail['message']);
        }
        $response = $this->get("api/v1/message/room/{$room_id}?room_type=random");
        $response
            ->assertOk()
            ->assertJsonPath('code', '1100');
    }

    public function sendMessage($match_users, $type, $message, $room_id)
    {
        $request = [
            'room_id'      => $room_id,
            'from_user_id' => $match_users['user_2_id'],
            'to_user_id'   => $match_users['user_1_id'],
            'message'      => $message,
            'room_type'    => $type
        ];
        $this->post('api/v1/message/send', $request);
    }
}
