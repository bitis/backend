<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\ClothesParam;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClothesParamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $params = ClothesParam::where('store_id', $this->store_id)
            ->when($request->input('type'), fn($query, $type) => $query->where('type', $type))
            ->paginate(getPerPage());
        return success($params);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function form(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $param = $id ? ClothesParam::find($id) : new ClothesParam(['store_id' => $this->store_id]);

        if ($param->store_id != $this->store_id) return fail();

        $param->fill($request->only([
            'type',
            'name',
            'code',
            'image'
        ]));

        $param->save();
        return success($param);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): JsonResponse
    {
        ClothesParam::where('store_id', $this->store_id)->where('id', $request->input('id'))->delete();

        return success();
    }
}
