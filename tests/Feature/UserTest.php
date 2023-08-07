<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testRegister()
    {
        $user_data = [
            'account'  => fake()->userName,
            'password' => fake()->password,
            'username' => fake()->userName,
            'gender'   => fake()->randomElement(['male', 'female'])
        ];
        $response = $this->post('api/v1/user/register', $user_data);
        $this->assertDatabaseHas('users', [
            'account'     => $user_data['account'],
            'username'    => $user_data['username'],
            'gender'      => $user_data['gender'],
        ]);
        $response->assertOk();
    }

    public function testLogin()
    {
        $user_data = [
            'account'  => fake()->name,
            'password' => fake()->password,
            'username' => fake()->userName,
            'gender'   => fake()->randomElement(['male', 'female'])
        ];
        $this->post('api/v1/user/register', [
            'account'     => $user_data['account'],
            'password'    => $user_data['password'],
            'username'    => $user_data['username'],
            'gender'      => $user_data['gender'],
        ]);
        #登入
        $response = $this->post('api/v1/user/login', [
            'account'  => $user_data['account'],
            'password' => $user_data['password'],
        ]);

        $response->assertOk();
    }

    public function testLogout()
    {
        $this->testLogin();
        $this->setUserData();
        $response = $this->post('api/v1/user/logout');
        $response->assertOk();
    }

    public function testEditProfile()
    {
        $this->setUserData();
        $response = $this->patch('api/v1/user/edit/profile', [
            'gender' => 'male'
        ]);
        $response->assertOk();
        $user = User::query()->first();
        $this->assertEquals('male', $user->gender);
    }
}
