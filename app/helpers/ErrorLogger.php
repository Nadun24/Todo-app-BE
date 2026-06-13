<?php

use Illuminate\Support\Facades\Log;

class ErrorLogger
{

    public static function logError(\Exception $e)
    {
        // Extract the error message
        $errorMessage = $e->getMessage();

        // Log the error with the context
        Log::error($errorMessage);
        Log::error($e->getTraceAsString());
    }
}
