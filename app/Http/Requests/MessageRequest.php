<?php

namespace App\Http\Requests;

use App\Rules\CheckRoom;
use App\Rules\CheckUser;
use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
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
            case 'getRoomMessage':
                $rules = [
                    'room_id'      => 'required|integer',
                ];
                break;
            case 'store':
                $rules = [
                    'room_id'      => [
                        'required',
                        'integer',
                        new CheckRoom(request()->all())
                    ],
                    'from_user_id' => [
                        'required',
                        'integer',
                        'exists:users,id'
                    ],
                    'to_user_id'   => 'required|integer|exists:users,id',
                    'message'      => 'required|string',
                    'room_type'    => 'required|string|in:personal,random'
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
