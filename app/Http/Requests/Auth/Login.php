<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class Login extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'account' => 'required|exists:users,account',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'account.required' => '账号信息必填',
            'account.exists' => '账号不存在',
        ];
    }
}
