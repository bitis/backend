<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Bulletin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BulletinController extends Controller
{
    public function index(): JsonResponse
    {
        return success(Bulletin::where('show_at', '>=', now())->where('is_show', 1)->orderBy('sort_num', 'desc')->get());
    }

    public function detail(Request $request): JsonResponse
    {
        return success(Bulletin::find($request->input('id')));
    }
}
