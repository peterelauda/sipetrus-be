<?php

namespace App\Http\Requests\Transaction;

use App\Enums\PaymentMethodEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;

class GetTransactionsRequest extends FormRequest
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
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d|after_or_equal:start_date',
            'payment_method' => ['nullable', 'string', new Enum(PaymentMethodEnum::class)],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'page.integer' => 'The page parameter must be a valid integer.',
            'page.min' => 'The page parameter must be at least 1.',
            'limit.integer' => 'The limit parameter must be a valid integer.',
            'limit.min' => 'The limit parameter must be at least 1.',
            'limit.max' => 'The limit parameter may not be greater than 100.',
            'start_date.date_format' => 'The start date does not match the format YYYY-MM-DD.',
            'end_date.date_format' => 'The end date does not match the format YYYY-MM-DD.',
            'end_date.after_or_equal' => 'The end date must be a date after or equal to start date.',
            'payment_method.string' => 'The payment method must be a valid string.',
            'payment_method.Illuminate\Validation\Rules\Enum' => 'The selected payment method is invalid.',
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
