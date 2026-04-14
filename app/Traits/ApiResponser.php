<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponser
{
    protected function success(string $message = '', $data = null, int $code = 200)
    {
        if (is_array($data) && isset($data['data'], $data['meta'])) {
            return response()->json(array_merge([
                'status' => 'Success',
                'message' => $message,
            ], $data), $code);
        }

        return response()->json([
            'status' => 'Success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function error($error, int $code = 500, $errors = null): JsonResponse
    {
        $message = match (true) {
            is_string($error) => $error,
            is_array($error) => ($error['message'] ?? 'Sorry an error occurred'),
            default => 'Sorry an error occurred',
        };

        return response()->json([
            'message' => $message,
            'errors' => $errors,
            'data' => null,
        ], $code);
    }
}