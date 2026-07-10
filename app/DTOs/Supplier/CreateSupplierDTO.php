<?php

namespace App\DTOs\Supplier;

class CreateSupplierDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly string $name,
        public readonly ?string $phone,
        public readonly ?string $email,
        public readonly ?string $address,
    ) {
    }

    public static function fromRequest($request): self
    {
        return new self(
            name: (string) $request->validated('name'),
            phone: $request->validated('phone'),
            email: $request->validated('email'),
            address: $request->validated('address'),
        );
    }
}
