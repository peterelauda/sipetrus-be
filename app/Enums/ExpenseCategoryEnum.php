<?php

namespace App\Enums;

enum ExpenseCategoryEnum: string
{
    case ELECTRICITY = 'Electricity';
    case INTERNET = 'Internet';
    case SALARY = 'Salary';
    case RENT = 'Rent';
    case TRANSPORT = 'Transport';
    case OTHER = 'Other';
}
