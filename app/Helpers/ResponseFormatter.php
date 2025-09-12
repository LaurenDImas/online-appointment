<?php
namespace App\Helpers;

use App\Enums\HttpCode;

class ResponseFormatter
{
    protected static $response = [
        'meta' => [
            'code' => 200,
            'status' => 'success',
            'messages' => null,
            'validations' => null,
            'response_date' => null,
        ],
        'data' => null,
    ];

    public static function success($data = null, $message = null): \Illuminate\Http\JsonResponse
    {
        self::$response['data'] = $data;
        self::$response['meta']['messages'] = $message;
        self::$response['meta']['response_date'] = now()->format('Y-m-d H:i:s');

        return response()->json(self::$response, self::$response['meta']['code']);
    }

    public static function error(HttpCode $code = HttpCode::INTERNAL_ERROR, $validations = null, $messages = null): \Illuminate\Http\JsonResponse
    {
        self::$response['meta']['status'] = 'error';
        self::$response['meta']['code'] = $code->value;
        self::$response['meta']['messages'] = $messages;
        self::$response['meta']['validations'] = $validations;
        self::$response['meta']['response_date'] = now()->format('Y-m-d H:i:s');

        return response()->json(self::$response, self::$response['meta']['code']);
    }
}
