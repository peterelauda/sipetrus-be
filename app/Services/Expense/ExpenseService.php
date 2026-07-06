<?php

namespace App\Services\Expense;

use App\DTOs\Expense\CreateExpenseDTO;
use App\DTOs\Expense\GetExpensesDTO;
use App\DTOs\Expense\UpdateExpenseDTO;
use App\Repositories\Contracts\Expense\ExpenseRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class ExpenseService
{
    protected $expenseRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(
        ExpenseRepositoryInterface $expenseRepository,
    ) {
        $this->expenseRepository = $expenseRepository;
    }

    public function createExpense(
        CreateExpenseDTO $dto
    ) {
        $storeId = auth()->user()->store_id;

        DB::beginTransaction();

        try {
            $expense = $this->expenseRepository->create([
                'store_id' => $storeId,
                'category' => $dto->category,
                'description' => $dto->description,
                'amount' => $dto->amount,
                'expense_date' => $dto->expenseDate,
            ]);

            DB::commit();

            return $expense;
        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th;
        }
    }

    public function getExpenses(
        GetExpensesDTO $dto
    ) {
        $storeId = auth()->user()->store_id;

        return $this->expenseRepository
            ->getExpenses(
                $storeId,
                $dto
            );
    }

    public function getExpenseById(
        string $id
    ) {
        $storeId = auth()->user()->store_id;

        $expense = $this->expenseRepository
            ->getExpenseById(
                $storeId,
                $id
            );

        if (!$expense) {
            throw new Exception(
                'Invalid expense ID'
            );
        }

        return $expense;
    }

    public function updateExpense(
        string $id,
        UpdateExpenseDTO $dto
    ) {
        $storeId = auth()->user()->store_id;

        $expense = $this->expenseRepository
            ->getExpenseById(
                $storeId,
                $id
            );

        if (!$expense) {
            throw new Exception(
                'Invalid expense ID'
            );
        }

        DB::beginTransaction();

        try {
            $updatedExpense = $this->expenseRepository
                ->update(
                    $id,
                    [
                        'category' => $dto->category,
                        'description' => $dto->description,
                        'amount' => $dto->amount,
                        'expense_date' => $dto->expenseDate,
                    ]
                );

            DB::commit();

            return $updatedExpense;
        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th;
        }
    }

    public function deleteExpense(
        string $id
    ) {
        $storeId = auth()->user()->store_id;

        $expense = $this->expenseRepository
            ->getExpenseById(
                $storeId,
                $id
            );

        if (!$expense) {
            throw new Exception(
                'Invalid expense ID'
            );
        }

        DB::beginTransaction();

        try {
            $this->expenseRepository
                ->delete($id);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th;
        }
    }
}
