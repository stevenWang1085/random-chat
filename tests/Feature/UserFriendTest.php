<?php

namespace Tests\Feature;

use App\Events\UnReadEvent;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class UserFriendTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Redis::flushdb();
    }

    public function testList()
    {
        $user_friend_data = $this->setFriendData();
        $this->patch("api/v1/friend/{$user_friend_data['id']}/update", [
            'status' => 'confirm'
        ]);
        $response = $this->get("api/v1/friend/{$user_friend_data['from_user_id']}/list?status=confirm");
        self::assertEquals($response->original['return_data'][0]['to_user_id'], $user_friend_data['to_user_id']);
        $response->assertOk()
            ->assertJsonPath('code', '1100');
    }

    public function testStore()
    {
        Event::fake();
        $user_1_data = $this->setUserData();
        $user_2_data = $this->setUserData();
        $response = $this->post('api/v1/friend/invite', [
            'to_user_id' => $user_1_data->id,
            'status'     => 'pending'
        ]);
        $response
            ->assertOk()
            ->assertJsonPath('code', '1207');
        Event::assertDispatched(UnReadEvent::class, function ($e) {
            return $e->type === 'add_friend';
        });
        $this->assertDatabaseHas('user_friends', [
            'from_user_id' => $user_2_data->id,
            'to_user_id'   => $user_1_data->id,
            'status'       => 'pending',
            'room_id'      => null
        ]);
    }

    public function testConfirmUpdate()
    {
        $user_friend_data = $this->setFriendData();
        $response = $this->patch("api/v1/friend/{$user_friend_data['id']}/update", [
            'status' => 'confirm'
        ]);
        $this->assertDatabaseHas('rooms', [
            'type' => 'personal'
        ]);
        $room = Room::query()->where('type', 'personal')->first();
        $this->assertDatabaseHas('user_rooms', [
            'user_id'      => $user_friend_data['from_user_id'],
            'room_id'      => $room['id']
        ]);
        $this->assertDatabaseHas('user_rooms', [
            'user_id'      => $user_friend_data['to_user_id'],
            'room_id'      => $room['id']
        ]);
        $this->assertDatabaseHas('user_friends', [
            'from_user_id' => $user_friend_data['from_user_id'],
            'to_user_id'   => $user_friend_data['to_user_id'],
            'status'       => 'confirm',
            'room_id'      => $room['id']
        ]);
        $response
            ->assertOk()
            ->assertJsonPath('code', '1300');
    }

    public function testRejectUpdate()
    {
        $user_friend_data = $this->setFriendData();
        $response = $this->patch("api/v1/friend/{$user_friend_data['id']}/update", [
            'status' => 'reject'
        ]);
        $this->assertDatabaseHas('user_friends', [
            'from_user_id' => $user_friend_data['from_user_id'],
            'to_user_id'   => $user_friend_data['to_user_id'],
            'status'       => 'reject',
            'room_id'      => null
        ]);
        $response
            ->assertOk()
            ->assertJsonPath('code', '1300');
    }
}
