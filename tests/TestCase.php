<?php

namespace Tests;

use App\Events\SuccessMatchEvent;
use App\Models\User;
use App\Models\UserFriend;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Event;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUserData()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $this->withHeader('Authorization', "Bearer {$token}");

        return $user;
    }

    public function setRandomMatchUser()
    {
        $user_1_id = $this->setUserData()->id;
        $request = [
            'select_gender' => 'all'
        ];
        $this->post('api/v1/random/start', $request);

        $user_2_id = $this->setUserData()->id;
        $request = [
            'select_gender' => 'all'
        ];
        $this->post('api/v1/random/start', $request);
        $this->artisan('match:user_v2');

        return [
            'user_1_id' => $user_1_id,
            'user_2_id' => $user_2_id,
        ];
    }

    public function setFriendData()
    {
        $user_1_data = $this->setUserData();
        $user_2_data = $this->setUserData();
        $this->post('api/v1/friend/invite', [
            'to_user_id' => $user_1_data->id,
            'status'     => 'pending'
        ]);

        return UserFriend::query()
            ->where('from_user_id', $user_2_data->id)
            ->where('to_user_id', $user_1_data->id)
            ->where('status', 'pending')
            ->first();
    }
}
