<?php

namespace App\Http\Requests\Expense;

use App\Enums\ExpenseCategoryEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;

class UpdateExpenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized.
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
            'category' => [
                'required',
                new Enum(ExpenseCategoryEnum::class),
            ],

            'description' => 'nullable|string|max:500',

            'amount' => 'required|numeric|min:0',

            'expense_date' => 'required|date',
        ];
    }

    /**
     * Custom messages.
     */
    public function messages(): array
    {
        return [
            'category.required' => 'Expense category is required.',
            'category.Illuminate\Validation\Rules\Enum' => 'The selected expense category is invalid.',

            'description.string' => 'Description must be a valid text.',
            'description.max' => 'Description may not exceed 500 characters.',

            'amount.required' => 'Expense amount is required.',
            'amount.numeric' => 'Expense amount must be a valid number.',
            'amount.min' => 'Expense amount cannot be less than 0.',

            'expense_date.required' => 'Expense date is required.',
            'expense_date.date' => 'Expense date must be a valid date.',
        ];
    }

    /**
     * Handle validation failure.
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
