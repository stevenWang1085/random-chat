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
        switch ($this->route()->getActionMethod()) {
            case 'register':
                $rules = [
                    'account'  => 'required|regex:/(^[a-zA-Z0-9 ]*$)/|min:6|max:30|unique:users',
                    'password' => 'required|regex:/(^[a-zA-Z0-9 ]*$)/|min:6',
                    'username' => 'required|min:1|max:30|unique:users',
                    'gender'   => 'required|in:male,female'
                ];
                break;
            case 'login':
                $rules = [
                    'account'  => 'required|regex:/(^[a-zA-Z0-9 ]*$)/|min:6|max:30',
                    'password' => 'required|regex:/(^[a-zA-Z0-9 ]*$)/|min:6|max:30',
                ];
                break;
            case 'editProfile':
                $rules = [
                    'gender'  => 'required|string|in:male,female',
                ];
                break;
            default:
                $rules = [];
        }

        return $rules;
    }
}
