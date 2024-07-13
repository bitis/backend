<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Enumerations\ProductType;
use App\Models\Level;
use App\Models\LevelProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return success(Level::withCount('members')->where('store_id', $this->store_id)->get());
    }

    public function form(Request $request): JsonResponse
    {
        $level = Level::findOr($request->input('id'), fn() => new Level(['store_id' => $this->store_id]));

        $level->fill($request->only(['name', 'discount', 'item_limit', 'item_count', 'remark']));

        $level->save();

        if ($level->item_limit) {
            $links = [];

            LevelProduct::where('level_id', $level->id)->delete();

            $linkProductIds = $request->input('product_ids', []);
            $linkServiceIds = $request->input('service_ids', []);

            foreach ($linkProductIds as $linkProductId) {
                $links[] = [
                    'level_id' => $level->id,
                    'product_id' => $linkProductId,
                    'type' => ProductType::Product->value
                ];
            }

            foreach ($linkServiceIds as $linkServiceId) {
                $links[] = [
                    'level_id' => $level->id,
                    'product_id' => $linkServiceId,
                    'type' => ProductType::Service->value
                ];
            }

            LevelProduct::insert($links);

            $level->item_count = count($links);
            $level->save();
        }

        return success($level);
    }

    /**
     * 详情
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function detail(Request $request): JsonResponse
    {
        $level = Level::with(['linkProductIds', 'linkServiceIds'])->find($request->input('id'));

        return success($level);
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
