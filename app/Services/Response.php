<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class Response
{

    public static function success($data = []): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public static function error(string $message, $statusCode = HttpResponse::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message
        ], $statusCode);
    }

}
