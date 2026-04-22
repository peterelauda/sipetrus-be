<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GetProductsRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'product_code' => 'nullable|string|max:10',
            'barcode' => 'nullable|string',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.string' => 'The product name must be a valid text string.',
            'name.max' => 'The product name may not be longer than 255 characters.',
            'product_code.string' => 'The product code must be a valid text string.',
            'product_code.max' => 'The product code may not be longer than 10 characters.',
            'barcode.string' => 'The barcode must be a valid text string.',
            'per_page.integer' => 'The items per page must be a valid integer.',
            'per_page.min' => 'The items per page must be at least 1.',
            'per_page.max' => 'You cannot request more than 100 items per page.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     * * @param Validator $validator
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors occurred.',
            'errors' => $validator->errors()
        ], 422));
    }
}
