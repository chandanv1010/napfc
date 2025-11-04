<?php

namespace App\Http\Requests\Scholar\Scholar;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'name' => 'required',
            'canonical' => 'required|unique:routers,canonical, '.$this->id.',module_id',
            'scholar_catalogue_id' => 'gt:0',
            'policy_id' => 'gt:0',
            'train_id' => 'gt:0',
        ];
    }

     public function messages(): array
    {
        return [
            'name.required' => 'Bạn chưa nhập vào ô tiêu đề.',
            'canonical.required' => 'Bạn chưa nhập vào ô đường dẫn',
            'canonical.unique' => 'Đường dẫn đã tồn tại, Hãy chọn đường dẫn khác',
            'scholar_catalogue_id.gt' => 'Bạn chưa chọn nhóm học bổng',
            'policy_id.gt' => 'Bạn chưa chọn chính sách',
            'train_id.gt' => 'Bạn chưa chọn hệ đào tạo',
        ];
    }
}
