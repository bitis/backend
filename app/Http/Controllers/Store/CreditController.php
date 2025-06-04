<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Credit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $credits = Credit::where('store_id', $this->store_id)
            ->orderBy('price')
            ->paginate(getPerPage());

        return success($credits);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function form(Request $request): JsonResponse
    {
        $credit = Credit::findOr($request->input('id'), fn() => new Credit(['store_id' => $this->store_id]));

        $credit->fill($request->only(['price', 'send', 'self']));

        $credit->save();

        return success();
    }

    /**
     * 详情
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function detail(Request $request): JsonResponse
    {
        $credit = Credit::where('store_id', $this->store_id)->find($request->input('id'))->toArray();

        return success($credit);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): JsonResponse
    {
        Credit::where('store_id', $this->store_id)->where('id', $request->input('id'))->delete();

        return success();
    }
}
