<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class InvalidCRMException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request): Response
    {
        return response()->json([
            'message' => 'Invalid CRM number provided.',
        ], 422);
    }
}
