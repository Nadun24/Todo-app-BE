<?php

class ResponseHelper
{

    public static function success($data = [], $message = 'Success',  $status = 200)
    {
        return response()->json([
            'status' => true, // false for 'error', true for 'success'
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    public static function error($data = null, $message = 'Error',  $status = 400)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => $data,
        ], $status);
    }
}
