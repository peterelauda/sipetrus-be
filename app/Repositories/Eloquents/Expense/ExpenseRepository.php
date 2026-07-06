<?php

namespace App\Repositories\Eloquents\Expense;

use App\DTOs\Expense\GetExpensesDTO;
use App\Models\Expense;
use App\Repositories\Contracts\Expense\ExpenseRepositoryInterface;
use App\Repositories\Eloquents\BaseRepository;

class ExpenseRepository extends BaseRepository implements ExpenseRepositoryInterface
{
    protected $model;

    public function __construct(
        Expense $model
    ) {
        $this->model = $model;
    }

    public function getExpenses(
        int $storeId,
        GetExpensesDTO $dto
    ) {
        return $this->model
            ->where('store_id', $storeId)
            ->when(
                $dto->category,
                function ($query, $category) {
                    $query->where(
                        'category',
                        'like',
                        '%' . $category . '%'
                    );
                }
            )
            ->when(
                $dto->startDate && $dto->endDate,
                function ($query) use ($dto) {
                    $query->whereBetween(
                        'expense_date',
                        [
                            $dto->startDate,
                            $dto->endDate,
                        ]
                    );
                }
            )
            ->orderByDesc('expense_date')
            ->paginate(
                $dto->perPage ?? 10
            );
    }

    public function getExpenseById(
        int $storeId,
        string $id
    ) {
        return $this->model
            ->where('store_id', $storeId)
            ->where('id', $id)
            ->first();
    }
}
