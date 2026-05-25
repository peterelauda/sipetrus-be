<?php

namespace App\Http\Requests\Stock;

use App\Enums\StockMovementTypeEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;

class GetStockMovementRequest extends FormRequest
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
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1|max:100',

            'product_id' => 'nullable|exists:products,id',

            'type' => ['nullable', new Enum(StockMovementTypeEnum::class)],

            'start_date' => 'nullable|date',

            'end_date' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'page.integer' => 'The page parameter must be a valid number.',
            'page.min' => 'The page number must be at least 1.',

            'limit.integer' => 'The limit parameter must be a valid number.',
            'limit.min' => 'The limit must be at least 1.',
            'limit.max' => 'The limit may not be greater than 100 items per page.',

            'product_id.exists' => 'The selected product does not exist in our records.',

            'type.enum' => 'The selected stock movement type is invalid.',

            'start_date.date' => 'The start date must be a valid date format.',
            'end_date.date' => 'The end date must be a valid date format.',
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
