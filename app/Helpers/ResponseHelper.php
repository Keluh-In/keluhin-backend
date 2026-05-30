<?php

namespace App\Helpers;

class ResponseHelper
{
    /**
     * SUCCESS RESPONSE
     */
    public static function success($data = null, $message = 'Success', $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * ERROR RESPONSE
     */
    public static function error($message = 'Error', $code = 400, $data = null)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * VALIDATION ERROR RESPONSE
     */
    public static function validation($errors, $message = 'Validation Error')
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $errors
        ], 422);
    }

    /**
     * NOT FOUND RESPONSE
     */
    public static function notFound($message = 'Data not found')
    {
        return response()->json([
            'status' => false,
            'message' => $message
        ], 404);
    }

    /**
     * UNAUTHORIZED RESPONSE
     */
    public static function unauthorized($message = 'Unauthorized')
    {
        return response()->json([
            'status' => false,
            'message' => $message
        ], 401);
    }

    /**
     * FORBIDDEN RESPONSE
     */
    public static function forbidden($message = 'Forbidden')
    {
        return response()->json([
            'status' => false,
            'message' => $message
        ], 403);
    }
}