<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RandomChatRequest extends FormRequest
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
            case 'startRandom':
                $rules = [
                    'select_gender' => 'required|string|in:all,male,female'
                ];
                break;
            case 'leaveRoom':
                $rules = [
                    'room_id'      => 'required|integer|exists:rooms,id',
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
