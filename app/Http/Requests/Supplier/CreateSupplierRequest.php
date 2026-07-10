<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateSupplierRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string|max:500',
        ];
    }

    /**
     * Validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Supplier name is required.',
            'name.string' => 'Supplier name must be a valid text.',
            'name.max' => 'Supplier name may not exceed 100 characters.',

            'phone.string' => 'Phone number must be a valid text.',
            'phone.max' => 'Phone number may not exceed 20 characters.',

            'email.email' => 'Email must be a valid email address.',
            'email.max' => 'Email may not exceed 100 characters.',

            'address.string' => 'Address must be a valid text.',
            'address.max' => 'Address may not exceed 500 characters.',
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
