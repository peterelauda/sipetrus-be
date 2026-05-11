<?php

namespace App\Http\Requests\Product;

use App\Enums\ProductCategoryEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;

class UpdateProductRequest extends FormRequest
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
            'cost_price' => 'nullable|numeric',
            'price' => 'nullable|numeric',
            'barcode' => 'nullable|string|max:35',
            'category' => ['nullable', new Enum(ProductCategoryEnum::class)],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.string' => 'The name must be a valid text string.',
            'name.max' => 'The name may not be greater than 255 characters.',

            'cost_price.numeric' => 'The cost price must be a number.',

            'price.numeric' => 'The selling price must be a number.',

            'barcode.string' => 'The barcode must be a valid string.',
            'barcode.max' => 'The barcode may not be greater than 35 characters.',

            'category.Illuminate\Validation\Rules\Enum' => 'The selected category is invalid.',
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
