<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

final class InvalidCROException extends Exception
{
    public function render()
    {
        return response()->json([
            'message' => 'Invalid CRO number provided.',
        ], 422);
    }
}
