<?php

namespace App\Http\Requests\Purchase;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreatePurchaseRequest extends FormRequest
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
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.cost_price' => 'required|numeric|min:0',
            'items.*.expired_date' => 'required|date|after:today',
        ];
    }

    /**
     * Validation messages.
     */
    public function messages(): array
    {
        return [
            'supplier_id.required' => 'Supplier is required.',
            'supplier_id.exists' => 'Selected supplier does not exist.',

            'purchase_date.required' => 'Purchase date is required.',
            'purchase_date.date' => 'Purchase date must be a valid date.',

            'items.required' => 'Purchase items are required.',
            'items.array' => 'Items must be an array.',
            'items.min' => 'At least one purchase item is required.',

            'items.*.product_id.required' => 'Product is required.',
            'items.*.product_id.exists' => 'Selected product does not exist.',

            'items.*.qty.required' => 'Quantity is required.',
            'items.*.qty.integer' => 'Quantity must be an integer.',
            'items.*.qty.min' => 'Quantity must be at least 1.',

            'items.*.cost_price.required' => 'Cost price is required.',
            'items.*.cost_price.numeric' => 'Cost price must be numeric.',
            'items.*.cost_price.min' => 'Cost price cannot be less than 0.',

            'items.*.expired_date.required' => 'Expired date is required.',
            'items.*.expired_date.date' => 'Expired date must be a valid date.',
            'items.*.expired_date.after' => 'Expired date must be greater than today.',
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
