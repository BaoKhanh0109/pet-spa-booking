<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
        'serviceName' => 'required|string|max:100|unique:services,serviceName',
        'description' => 'nullable|string',
        'price'       => 'required|numeric|min:0'
        ];
    }
    public function messages()
    {
        return [
            'serviceName.required' => 'Tên dịch vụ không được để trống.',
            'serviceName.unique'   => 'Tên dịch vụ đã tồn tại.',
            'price.numeric'        => 'Giá tiền phải là một con số.',
            'price.min'            => 'Giá tiền không được âm.'
        ];
    }
}
?>