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
            'mobile' => 'required|exists:users,mobile',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'mobile.required' => '账号信息必填',
            'mobile.exists' => '账号不存在',
        ];
    }
}
