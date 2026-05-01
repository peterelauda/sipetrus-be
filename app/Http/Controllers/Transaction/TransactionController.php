<?php

namespace App\Http\Controllers\Transaction;

use App\DTOs\Transaction\StoreTransactionDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\StoreTransactionRequest;
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
