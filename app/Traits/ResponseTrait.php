<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseTrait
{
    public static function Success($data, $message, $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 1,
            'data' => $data,
            'message' => $message
        ], $code);
    }

    public static function Error($data, $message, $code = 400): JsonResponse
    {
        return response()->json([
            'status' => 0,
            'data' => $data,
            'message' => $message
        ], $code);
    }


    public static function Validation($data, $message, $code = 422): JsonResponse
    {
        return response()->json([
            'status' => 0,
            'data' => $data,
            'message' => $message
        ], $code);
    }
}
