<?php

namespace App\Http\Requests\School\School;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'code' => 'required',
            'rank' => 'required',
            'school_catalogue_id' => 'gt:0',
            'area' => 'gt:0',
            'canonical' => 'required|unique:routers',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Bạn chưa nhập vào ô tiêu đề.',
            'code.required' => 'Bạn chưa nhập mã trường.',
            'rank.required' => 'Bạn chưa nhập xếp hạng.',
            'school_catalogue_id.gt' => 'Bạn chưa chọn loại hình trường.',
            'area_id.gt' => 'Bạn chưa chọn khu vực.',
            'canonical.required' => 'Bạn chưa nhập vào ô đường dẫn',
            'canonical.unique' => 'Đường dẫn đã tồn tại, Hãy chọn đường dẫn khác',
        ];
    }
}
