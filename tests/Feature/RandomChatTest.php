<?php

namespace Tests\Feature;

use App\Events\LeaveRandomRoomEvent;
use App\Events\SuccessMatchEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class RandomChatTest extends TestCase
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
    public function testStartRandom()
    {
        $user_id = $this->setUserData()->id;
        $request = [
            'select_gender' => 'male'
        ];
        $response = $this->post('api/v1/random/start', $request);
        $status = Redis::hexists('random_pending', "user_id_{$user_id}");
        self::assertEquals(1, $status);
        $response->assertOk();
    }

    public function testMatchRandomUserEvent()
    {
        Event::fake();
        $this->setUserData()->id;
        $request = [
            'select_gender' => 'all'
        ];
        $this->post('api/v1/random/start', $request);
        $this->setUserData()->id;
        $request = [
            'select_gender' => 'all'
        ];
        $this->post('api/v1/random/start', $request);
        $this->artisan('match:user_v2');

        Event::assertDispatchedTimes(SuccessMatchEvent::class, 2);
    }

    public function testCancelRandom()
    {
        $user_id = $this->setUserData()->id;
        $response = $this->post('api/v1/random/cancel');
        $status = Redis::hexists('random_pending', "user_id_{$user_id}");
        self::assertEquals(0, $status);
        $response->assertOk();
    }

    public function testCheckRandomChat()
    {
        #測試配對
        $match_user = $this->setRandomMatchUser();
        $response = $this->post('api/v1/random/check');
        $user_1_pending_status = Redis::hexists('random_pending', "user_id_{$match_user['user_1_id']}");
        $user_2_pending_status = Redis::hexists('random_pending', "user_id_{$match_user['user_2_id']}");
        $user_1_status = Redis::hexists('random_complete', "user_id_{$match_user['user_1_id']}");
        $user_2_status = Redis::hexists('random_complete', "user_id_{$match_user['user_2_id']}");
        $room_id = Redis::get('current_random_room_id');
        $room_status = Redis::exists("random_room_id_{$room_id}");
        self::assertEquals(0, $user_1_pending_status);
        self::assertEquals(0, $user_2_pending_status);
        self::assertEquals(1, $user_1_status);
        self::assertEquals(1, $user_2_status);
        self::assertEquals(1, $room_status);
        $response
            ->assertOk()
            ->assertJsonPath('code', '1205');
    }

    public function testLeaveRandomRoom()
    {
        Event::fake();
        $match_user = $this->setRandomMatchUser();
        $room_id =  Redis::get('current_random_room_id');
        $request = [
            'to_user_id' => $match_user['user_1_id'],
            'room_id'    => $room_id
        ];
        $response = $this->post('api/v1/random/leave', $request);
        $user_1_status = Redis::hexists('random_complete', "user_id_{$match_user['user_1_id']}");
        $user_2_status = Redis::hexists('random_complete', "user_id_{$match_user['user_2_id']}");
        $room_status = Redis::exists("random_room_id_{$room_id}");
        $message_status = Redis::exists("random_room_message_room_id_{$room_id}");
        self::assertEquals(0, $user_1_status);
        self::assertEquals(0, $user_2_status);
        self::assertEquals(0, $room_status);
        self::assertEquals(0, $message_status);
        $response
            ->assertOk()
            ->assertJsonPath('code', '1401');
        Event::assertDispatched(LeaveRandomRoomEvent::class);
    }
}
