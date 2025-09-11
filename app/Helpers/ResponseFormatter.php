<?php
namespace App\Helpers;

use App\Enums\HttpCode;

class ResponseFormatter
{
    protected static $response = [
        'meta' => [
            'code' => HttpCode::OK,
            'status' => 'success',
            'message' => [],
            'validations' => null,
            'response_date' => null,
        ],
        'data' => null,
    ];

    public static function success($code = HttpCode::OK, $data = null, $message = [])
    {
        self::$response['data'] = $data;
        self::$response['meta']['message'] = $message;
        self::$response['meta']['response_date'] = now()->format('Y-m-d H:i:s');

        return response()->json(self::$response, $code);
    }

    public static function error($code = HttpCode::INTERNAL_ERROR, $validations = null, $messages = [])
    {
        self::$response['meta']['status'] = 'error';
        self::$response['meta']['code'] = $code;
        self::$response['meta']['messages'] = $messages;
        self::$response['meta']['validations'] = $validations;
        self::$response['meta']['response_date'] = now()->format('Y-m-d H:i:s');

        return response()->json(self::$response, $code);
    }
}
