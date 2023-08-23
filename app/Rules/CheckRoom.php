<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class CheckRoom implements Rule
{
    private $request_data;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($request_data)
    {
        $this->request_data = $request_data;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $room_type = $this->request_data['room_type'];
        $room_id = $this->request_data['room_id'];
        $from_user_id = $this->request_data['from_user_id'];
        $to_user_id = $this->request_data['to_user_id'];
        $check_from_user_id = Auth::id();

        #檢查房間是否對應正確使用者
        $user_ids = json_decode(Redis::get("{$room_type}_room_id_{$room_id}"), true);
        if (! is_null($user_ids)) {
            if ($check_from_user_id != $from_user_id) return false;
            $check = array_diff($user_ids['user_id'], [$from_user_id, $to_user_id]);
            if (count($check) != 0) return false;
        } else {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'wrong chat message';
    }
}
