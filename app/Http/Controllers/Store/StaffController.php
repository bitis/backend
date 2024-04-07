<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return success(User::where('store_id', $this->store_id)->simplePaginate(getPerPage()));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function form(Request $request): JsonResponse
    {
        $user = $request->input('id') ? User::where('store_id', $this->store_id)
            ->findOr($request->input('id'), fn() => new User(['store_id' => $this->store_id]))
            : new User(['store_id' => $this->store_id]);

        $user->fill($request->only(['name', 'avatar', 'mobile', 'password', 'status']));
        $user->password = bcrypt($request->input('password', config('default.password')));
        $user->save();

        return success();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): JsonResponse
    {
        Product::where('store_id', $this->store_id)->where('id', $request->input('id'))->delete();

        return success();
    }

}
