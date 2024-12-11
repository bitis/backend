<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * 商品单位
     */
    public function index(): JsonResponse
    {
        return success(Unit::whereIn('store_id', [0, $this->store_id])->orderBy('id', 'desc')->get());
    }

    public function form(Request $request): JsonResponse
    {
        $unit = Unit::findOr(request('id'), fn() => new Unit(['store_id' => $this->store_id]));

        $unit->name = $request->input('name');

        $unit->save();

        return success($unit);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): JsonResponse
    {
        Unit::where('store_id', $this->store_id)->where('id', $request->input('id'))->delete();

        return success();
    }
}
