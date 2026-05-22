<?php

namespace App\Exceptions;

use Exception;

final class InvalidProfessionalException extends Exception
{
    public function render()
    {
        return response()->json([
            'message' => 'Invalid professional type.'
        ], 422);
    }
}
