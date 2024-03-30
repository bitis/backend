<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Enumerations\SpecType;
use App\Models\Product;
use App\Models\ProductContent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    /**
     * 列表
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $products = Product::where('store_id', $this->store_id)
            ->when($request->get('type'), fn($query, $type) => $query->where('type', $type))
            ->when($request->get('category'), fn($query, $category) => $query->where('category_id', $category))
            ->when($request->get('keyword'), fn($query, $keyword) => $query->where('name', 'like', "%{$keyword}%"))
            ->simplePaginate(getPerPage());

        return success($products);
    }

    /**
     * 创建、编辑
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function form(Request $request): JsonResponse
    {
        $product = $request->input('id') ? Product::where('store_id', $this->store_id)
            ->findOr($request->input('id'), fn() => new Product(['store_id' => $this->store_id]))
            : new Product(['store_id' => $this->store_id]);

        $product->fill($request->all());
        $product->save();

        if ($request->input('content')) {
            ProductContent::updateOrCreate(['product_id' => $product->id], ['content' => $request->input('content')]);
        }

        if ($request->input('images')) {
            $product->images()->delete();
            $product->images()->createMany($request->input('images'));
        }

        if ($product->spec_type == SpecType::Multi) {
            $specs = $request->input('specs');
            // TODO
        }

        return success($product);
    }
}
