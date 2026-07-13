<?php

namespace App\Http\Requests\Purchase;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class GetPurchasesRequest extends FormRequest
{
    /**
     * Determine whether the user is authorized.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [
            'supplier_id' => 'nullable|exists:suppliers,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }

    /**
     * Validation messages.
     */
    public function messages(): array
    {
        return [
            'supplier_id.exists' => 'Selected supplier does not exist.',

            'start_date.date' => 'Start date must be a valid date.',

            'end_date.date' => 'End date must be a valid date.',
            'end_date.after_or_equal' => 'End date must be greater than or equal to start date.',

            'per_page.integer' => 'Per page must be an integer.',
            'per_page.min' => 'Per page must be at least 1.',
            'per_page.max' => 'Per page may not exceed 100.',
        ];
    }

    /**
     * Failed validation response.
     */
    protected function failedValidation(
        Validator $validator
    ) {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
