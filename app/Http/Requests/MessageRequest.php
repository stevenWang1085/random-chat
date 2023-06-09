<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\Console\Input\Input;

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
                    'room_id'      => 'required|integer',
                    'from_user_id' => 'required|integer|exists:users,id',
                    'to_user_id'   => 'required|integer|exists:users,id',
                    'message'      => 'required|string'
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
