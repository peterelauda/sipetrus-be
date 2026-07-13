<?php

namespace App\Http\Controllers\Expense;

use App\DTOs\Expense\CreateExpenseDTO;
use App\DTOs\Expense\GetExpensesDTO;
use App\DTOs\Expense\UpdateExpenseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Expense\CreateExpenseRequest;
use App\Http\Requests\Expense\GetExpensesRequest;
use App\Http\Requests\Expense\UpdateExpenseRequest;
use App\Http\Resources\Expense\ExpenseResource;
use App\Services\Expense\ExpenseService;
use App\Traits\ApiLogger;
use App\Traits\ApiResponser;

class ExpenseController extends Controller
{
    use ApiLogger, ApiResponser;

    private $expenseService;

    public function __construct(
        ExpenseService $expenseService
    ) {
        $this->expenseService = $expenseService;
    }

    public function createExpense(
        CreateExpenseRequest $request
    ) {
        try {
            $data = $this->expenseService
                ->createExpense(
                    CreateExpenseDTO::fromRequest($request)
                );

            return $this->success(
                'Expense created successfully',
                new ExpenseResource($data),
                200
            );
        } catch (\Throwable $th) {
            $this->logError(
                'Failed to create expense: ',
                $th
            );

            return $this->error(
                'Failed to create expense',
                400,
                $th->getMessage()
            );
        }
    }

    public function getExpenses(
        GetExpensesRequest $request
    ) {
        try {
            $data = $this->expenseService
                ->getExpenses(
                    GetExpensesDTO::fromRequest($request)
                );

            return $this->success(
                'Expenses retrieved successfully',
                ExpenseResource::collection($data),
                200
            );
        } catch (\Throwable $th) {
            $this->logError(
                'Failed to get expenses: ',
                $th
            );

            return $this->error(
                'Failed to retrieve expenses',
                400,
                $th->getMessage()
            );
        }
    }

    public function getExpenseById(
        string $id
    ) {
        try {
            $data = $this->expenseService
                ->getExpenseById($id);

            return $this->success(
                'Expense retrieved successfully',
                new ExpenseResource($data),
                200
            );
        } catch (\Throwable $th) {
            $this->logError(
                'Failed to get expense: ',
                $th
            );

            return $this->error(
                'Failed to retrieve expense',
                400,
                $th->getMessage()
            );
        }
    }

    public function updateExpense(
        string $id,
        UpdateExpenseRequest $request
    ) {
        try {
            $data = $this->expenseService
                ->updateExpense(
                    $id,
                    UpdateExpenseDTO::fromRequest($request)
                );

            return $this->success(
                'Expense updated successfully',
                new ExpenseResource($data),
                200
            );
        } catch (\Throwable $th) {
            $this->logError(
                'Failed to update expense: ',
                $th
            );

            return $this->error(
                'Failed to update expense',
                400,
                $th->getMessage()
            );
        }
    }

    public function deleteExpense(
        string $id
    ) {
        try {
            $this->expenseService
                ->deleteExpense($id);

            return $this->success(
                'Expense deleted successfully',
                null,
                204
            );
        } catch (\Throwable $th) {
            $this->logError(
                'Failed to delete expense: ',
                $th
            );

            return $this->error(
                'Failed to delete expense',
                400,
                $th->getMessage()
            );
        }
    }
}
