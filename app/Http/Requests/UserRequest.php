<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        switch ($this->route()->getActionName()) {
            case 'register':
                $rule = [
                    'account'  => 'required|account|exists:users',
                    'password' => 'required|min:6'
                ];
                break;
            case 'login':
                $rule = [];
                break;
            case 'logout':
                $rule = [];
                break;
            default:
                $rule = [];
        }

        return $rule;
    }
}
