<?php

namespace App\Http\Requests\Product;

use App\Enums\StockMovementTypeEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;

class AdjustStockRequest extends FormRequest
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
            'type' => ['required', new Enum(StockMovementTypeEnum::class)],
            'qty' => 'required|numeric|min:1',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'type.required' => 'The stock movement type is required.',
            'type.Illuminate\Validation\Rules\Enum' => 'The selected type is invalid.',

            'qty.required' => 'The quantity field cannot be empty.',
            'qty.numeric' => 'The quantity must be a valid number.',
            'qty.min' => 'The quantity must be at least 1.',
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
