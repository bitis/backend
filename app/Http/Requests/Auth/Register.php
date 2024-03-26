<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class Register extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'mobile' => 'required|unique:users,account',
            'invite_code' => 'required|exists:companies'
        ];
    }

    public function messages(): array
    {
        return [
            'mobile.required' => '请填写手机号',
            'mobile.unique' => '账号已存在',
            'invite_code.required' => '请填写邀请码',
            'invite_code.exists' => '当前邀请码无效',
        ];
    }
}
