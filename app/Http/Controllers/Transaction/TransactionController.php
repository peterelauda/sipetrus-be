<?php

namespace App\Http\Controllers\Transaction;

use App\DTOs\Transaction\GetTransactionDTO;
use App\DTOs\Transaction\StoreTransactionDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\GetTransactionsRequest;
use App\Http\Requests\Transaction\StoreTransactionRequest;
use App\Http\Resources\Transaction\GetTransactionDetailResource;
use App\Http\Resources\Transaction\GetTransactionResource;
use App\Http\Resources\Transaction\StoreTransactionResource;
use App\Services\Transaction\TransactionService;
use App\Traits\ApiLogger;
use App\Traits\ApiResponser;

class TransactionController extends Controller
{
    use ApiLogger, ApiResponser;

    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function getTransactions(GetTransactionsRequest $request)
    {
        try {
            $data = $this->transactionService
                ->getTransactions(GetTransactionDTO::fromRequest($request));

            return $this->success('Get transaction successfully', GetTransactionResource::collection($data), 200);
        } catch (\Throwable $th) {
            $this->logError('Failed when get transaction', $th);

            return $this->error('Failed when get transaction', 400, $th->getMessage());
        }
    }

    public function getTransactionById($id)
    {
        try {
            $data = $this->transactionService->getTransactionById($id);

            return $this->success('Get transaction detail successfully', new GetTransactionDetailResource($data), 200);
        } catch (\Throwable $th) {
            $this->logError('Failed when get detail transaction', $th);

            return $this->error('Failed when get detail transaction', 400, $th->getMessage());
        }
    }

    public function storeTransaction(StoreTransactionRequest $request)
    {
        try {
            $data = $this->transactionService
                ->storeTransaction(StoreTransactionDTO::fromRequest($request));

            return $this->success('Store transaction successfully', new StoreTransactionResource($data), 200);
        } catch (\Exception $e) {
            $this->logError('Failed when store transaction', $e);

            return $this->error('Failed when store transaction', $e->getCode(), $e->getMessage());
        }
    }
}
