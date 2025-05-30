<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\StoreJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return success(StoreJob::where('store_id', $this->store_id)->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function form(Request $request): JsonResponse
    {
        $job = $request->input('id') ? StoreJob::where('store_id', $this->store_id)
            ->findOr($request->input('id'), fn() => new StoreJob(['store_id' => $this->store_id]))
            : new StoreJob(['store_id' => $this->store_id]);

        $job->fill($request->only(['name']));
        $job->save();

        return success();
    }

    public function detail(Request $request): JsonResponse
    {
        $job = StoreJob::where('store_id', $this->store_id)->find($request->input('id'));

        if (empty($job)) return fail();

        return success($job);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): JsonResponse
    {
        StoreJob::where('store_id', $this->store_id)->where('id', $request->input('id'))->delete();

        return success();
    }

    public function jobs(): JsonResponse
    {
        return success(StoreJob::where('store_id', $this->store_id)->get());
    }

}
