<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBoxRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'product' => 'required|array',
            "product.*.length" => ['required'],
            "product.*.width" => ['required'],
            "product.*.height" => ['required'],
            "product.*.weight" => ['required'],
            "product.*.quantity" => ['required']

        ];
    }
}
