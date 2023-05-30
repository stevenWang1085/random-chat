<?php

namespace App\Http\Requests;

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
                    'room_id'      => 'required|integer|exists:rooms,id',
                ];
                break;
            case 'sendMessage':
                $rules = [
                    'room_id'      => 'required|integer|exists:rooms,id',
                    'from_user_id' => 'required|integer|exists:users,id',
                    'to_user_id'   => 'required|integer|exists:users,id',
                    'message'      => 'required|string'
                ];
        }

        return $rules;
    }
}
