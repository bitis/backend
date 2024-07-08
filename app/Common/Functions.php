<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

function fail($msg = 'FAIL', $code = -1): JsonResponse
{
    return response()->json([
        'code' => $code,
        'msg' => $msg,
        'data' => null
    ]);
}

function success($data = null): JsonResponse
{
    if ($data instanceof LengthAwarePaginator) {
        return response()->json([
            'code' => 0,
            'msg' => 'OK',
            'data' => [
                'list' => $data->items(),
                'total' => $data->total(),
            ]
        ]);
    }

    return response()->json([
        'code' => 0,
        'msg' => 'OK',
        'data' => $data
    ]);
}

function getPerPage(): int
{
    return request()->input('pageSize') ?: config('default.pageSize');
}

