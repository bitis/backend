<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Spec;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SpecController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return success(Spec::where('store_id', $this->store_id)->get());
    }

    /**
     * 创建修改
     */
    public function form(Request $request): JsonResponse
    {
        $spec = Spec::findOr($request->input('id'), fn() => new Spec(['store_id' => $this->store_id]));

        $spec->fill($request->only(['name', 'values']));

        $spec->save();

        return success($spec);
    }

}
