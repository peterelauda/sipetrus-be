<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Throwable;

trait ApiLogger
{
    protected function logSuccess(
        string $message,
        array $context = []
    ): void {
        Log::info($message, array_merge([
            'status' => 'success',
            'class' => static::class,
        ], $context));
    }

    protected function logError(
        string $message,
        ?Throwable $exception = null,
        array $context = []
    ): void {
        Log::error($message, array_merge([
            'status' => 'error',
            'class' => static::class,
            'exception' => [
                'message' => $exception->getMessage() ?? null,
                'file' => $exception->getFile() ?? null,
                'line' => $exception->getLine() ?? null,
            ],
        ], $context));
    }
}