<?php

namespace App\Repositories\Contracts\Expense;

use App\DTOs\Expense\GetExpensesDTO;
use App\Repositories\Contracts\BaseRepositoryInterface;

interface ExpenseRepositoryInterface extends BaseRepositoryInterface
{
    public function getExpenses(
        int $storeId,
        GetExpensesDTO $dto
    );

    public function getExpenseById(
        int $storeId,
        string $id
    );
}
