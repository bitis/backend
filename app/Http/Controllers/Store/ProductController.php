<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductContent;
use App\Models\ProductItem;
use App\Models\ProductSpec;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

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
        $products = Product::with('category:id,name')
            ->where('store_id', $this->store_id)
            ->when($request->get('type'), fn($query, $type) => $query->where('type', $type))
            ->when($request->get('category'), fn($query, $category) => $query->where('category_id', $category))
            ->when($request->get('keyword'), fn($query, $keyword) => $query->where('name', 'like', "%{$keyword}%"))
            ->paginate(getPerPage());

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
        $product = Product::where('store_id', $this->store_id)
            ->findOr($request->input('id'), fn() => new Product(['store_id' => $this->store_id]));

        $product->fill($request->all());
        $product->save();

        if ($unit = $request->input('unit')) {
            if (Unit::whereIn('store_id', [0, $this->store_id])->where('name', $unit)->doesntExist()) {
                Unit::create(['store_id' => $this->store_id, 'name' => $unit]);
            }
        }

        if ($request->input('content')) {
            ProductContent::updateOrCreate(['product_id' => $product->id], ['content' => $request->input('content')]);
        }

//        if ($request->input('images')) {
//            $product->images()->delete();
//            $images = array_map(fn ($path) => ['path' => $path], $request->input('images'));
//            $product->images()->createMany($images);
//        }

        if ($request->input('multi_spec')) {
            $newSpecs = [];

            ProductSpec::where('product_id', $product->id)->delete(); // 删除原有规格
            ProductItem::where('product_id', $product->id)->delete(); // 删除原有SKU

            foreach ($request->input('items') as $item) {

                $productItem = ProductItem::create(array_merge($item, ['product_id' => $product->id]));

                foreach ($item['specs'] as $spec) {
                    $newSpecs[] = array_merge($spec, [
                        'product_id' => $product->id,
                        'product_item_id' => $productItem->id
                    ]);
                }
            }

            ProductSpec::insert($newSpecs);
        } else {
            ProductItem::updateOrCreate([
                'product_id' => $product->id,
            ], $request->only(['price', 'original_price', 'member_price', 'stock', 'bar_code', 'duration']));
        }

        return success($product);
    }

    /**
     * 删除
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        Product::where('store_id', $this->store_id)->where('id', $request->input('id'))->delete();

        return success();
    }
}
