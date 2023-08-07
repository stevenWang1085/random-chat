<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserFriendRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        switch ($this->route()->getActionMethod()) {
            case 'list':
                $rules = [
                    'user_id' => 'required|integer|exists:users,id',
                    'status'  => 'required|string|in:pending,confirm,reject,block,remove',
                ];
                break;
            case 'store':
                $rules = [
                    'to_user_id'   => 'required|integer|exists:users,id|notIn:' . Auth::id(),
                    'status'       => 'required|string|in:pending'
                ];
                break;
            case 'update':
                $rules = [
                    'user_friend_id' => 'required|integer|exists:user_friends,id',
                    'status'         => 'required|string|in:pending,confirm,reject,block,remove',
                ];
                break;
            default:
                $rules = [];
        }

        return $rules;
    }

    public function all($keys = null)
    {
        return array_merge(parent::all(), $this->route()->parameters());
    }
}
