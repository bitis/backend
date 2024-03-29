<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return success(Category::where('store_id', $this->store_id)->orderBy('sort', 'asc')->get());
    }

    public function form(Request $request): JsonResponse
    {
        $category = Category::findOr(request('id'), new Category(['store_id' => $this->store_id]));

        $category->fill($request->only(['name', 'sort']));

        $category->save();

        return success($category);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): JsonResponse
    {
        Category::where('store_id', $this->store_id)->where('id', $request->input('id'))->delete();

        return success();
    }
}
