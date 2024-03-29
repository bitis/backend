<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Level;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return success(Level::where('store_id', $this->store_id)->get());
    }

    public function form(Request $request): JsonResponse
    {
        $category = Level::findOr(request('id'), new Level(['store_id' => $this->store_id]));

        $category->fill($request->only(['name', 'flag', 'discount']));

        $category->save();

        return success($category);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): JsonResponse
    {
        Level::where('store_id', $this->store_id)->where('id', $request->input('id'))->delete();

        return success();
    }
}
