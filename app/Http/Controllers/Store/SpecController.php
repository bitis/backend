<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\SpecValue;
use App\Models\Spec;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SpecController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return success(Spec::with('values')->where('store_id', $this->store_id)->get());
    }

    /**
     * 创建修改
     */
    public function form(Request $request): JsonResponse
    {
        $spec = Spec::findOr($request->input('id'), fn() => new Spec(['store_id' => $this->store_id]));

        $spec->name = $request->input('name');

        $spec->save();

        $exists = SpecValue::where('spec_id', $spec->id)->get();

        foreach ($exists as $exist) {
            if (!in_array($exist->value, $request->input('values'))) {
                $exist->delete();
            }
        }

        $existValues = $exists->pluck('value')->toArray();
        foreach ($request->input('values') as $value) {
            if (!in_array($value, $existValues)) {
                SpecValue::create([
                    'spec_id' => $spec->id,
                    'value' => $value
                ]);
            }
        }

        return success();
    }

    public function destroy(Request $request): JsonResponse
    {
        Spec::where('store_id', $this->store_id)->where('id', $request->input('id'))->delete();

        return success();
    }

}
