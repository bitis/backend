<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Enumerations\ProductType;
use App\Models\Grade;
use App\Models\GradeProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        if (request('base')) {
            return success(
                array_merge(
                    [['id' => 0, 'name' => '暂无等级']],
                    Grade::where('store_id', $this->store_id)->select(['id', 'name'])->get()->toArray()
                )
            );
        }
        return success(Grade::withCount('members')->where('store_id', $this->store_id)->get());
    }

    public function form(Request $request): JsonResponse
    {
        $level = Grade::findOr($request->input('id'), fn() => new Grade(['store_id' => $this->store_id]));

        $level->fill($request->only(['name', 'has_discount', 'discount', 'item_limit', 'item_count', 'remark']));

        $level->save();

        if ($level->item_limit) {
            $links = [];

            GradeProduct::where('level_id', $level->id)->delete();

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

            GradeProduct::insert($links);

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
        $grade = Grade::with(['linkProductIds', 'linkServiceIds'])->find($request->input('id'));

        return success($grade);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): JsonResponse
    {
        Grade::where('store_id', $this->store_id)->where('id', $request->input('id'))->delete();

        return success();
    }
}
