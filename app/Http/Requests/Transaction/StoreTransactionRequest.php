<?php

namespace App\Http\Requests\Transaction;

use App\Enums\PaymentMethodEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;

class StoreTransactionRequest extends FormRequest
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
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => ['required', new Enum(PaymentMethodEnum::class)],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'items.required' => 'Items are required.',
            'items.array' => 'Items must be an array.',
            'items.min' => 'At least one item is required.',
            'items.*.product_id.required' => 'Product ID is required.',
            'items.*.product_id.exists' => 'The selected product does not exist.',
            'items.*.qty.required' => 'Quantity is required.',
            'items.*.qty.integer' => 'Quantity must be an integer.',
            'items.*.qty.min' => 'Quantity must be at least 1.',
            'paid_amount.required' => 'Paid amount is required.',
            'paid_amount.numeric' => 'Paid amount must be a number.',
            'paid_amount.min' => 'Paid amount cannot be negative.',
            'payment_method.required' => 'Payment method is required.',
            'payment_method.enum' => 'Invalid payment method selected.',
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
