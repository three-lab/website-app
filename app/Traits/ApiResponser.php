<?php

namespace App\Traits;

trait ApiResponser
{
    protected function success(array $data, string $message = '', int $code = 200)
    {
        return response()->json([
            'meta' => [
                'code' => $code,
                'status' => 'success',
                'message' => $message,
            ],
            'data' => $data,
        ], $code);
    }

    protected function error(array $data, string $message = '', int $code = 400)
    {
        return response()->json([
            'meta' => [
                'code' => $code,
                'status' => 'error',
                'message' => $message,
            ],
            'data' => $data,
        ], $code);
    }

    protected function validationError(array $errors)
    {
        return $this->error($errors, 'Validation Error', 422);
    }
}
