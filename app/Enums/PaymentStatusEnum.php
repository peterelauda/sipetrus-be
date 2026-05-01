<?php

namespace App\Enums;

enum PaymentStatusEnum: string
{
    case PAID = 'paid';
    case PENDING = 'pending';
    case CANCELLED = 'cancelled';
}
