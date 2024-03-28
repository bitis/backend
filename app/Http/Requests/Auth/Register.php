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
            'store_name' => 'required',
            'mobile' => 'required|unique:users,mobile',
            'password' => 'required|min:6',
            'verify_code' => 'required',
            'industry_id' => 'required|exists:industries,id',
        ];
    }

    public function messages(): array
    {
        return [
            'store_name.required' => '请填写门店名称',
            'mobile.required' => '请填写手机号',
            'mobile.unique' => '账号已存在',
            'password.required' => '请填写密码',
            'password.min' => '密码最少6位',
            'verify_code.required' => '请填写验证码',
            'industry_id.required' => '请选择行业',
            'industry_id.industries' => '行业代码错误',
        ];
    }
}
