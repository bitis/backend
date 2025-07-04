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

    public function detail(Request $request)
    {
        $bulletin = Bulletin::find($request->input('id'));

        if ($request->isMethod('POST')) return success($bulletin);

        return view('store.bulletin.detail', ['bulletin' => $bulletin]);
    }
}
