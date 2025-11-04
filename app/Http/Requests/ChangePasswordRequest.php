<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|string|same:password'
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'Bạn chưa nhập vào ô mật khẩu',
            'password.min' => 'Mật khẩu phải có tối thiểu 6 ký tự',
            'confirm_password.required' => 'Bạn chưa nhập vào ô Nhập lại mật khẩu.',
            'confirm_password.same' => 'Mật khẩu không khớp.',
        ];
    }
}