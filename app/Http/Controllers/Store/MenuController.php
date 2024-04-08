<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\UserPermission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $menus = Menu::all()->toArray();

        $permissions = null;
        if (!$this->user->is_admin)
            $permissions = UserPermission::where('user_id', $this->user->id)->first();

        foreach ($menus as &$menu) {
            $menu['checked'] = false;

            if ($this->user->is_admin) {
                $menu['checked'] = true;
                continue;
            }

            if (in_array($menu['id'], $permissions->permissions)) {
                $menu['checked'] = true;
            }
        }

        return success($menus);
    }

    /**
     * 新增、编辑
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function form(Request $request): JsonResponse
    {
        if (!$this->user->system_operator) {
            return fail('没有操作权限');
        }

        $menu = Menu::findOr($request->input('id'), fn() => new Menu());

        $menu->fill($request->all());
        $menu->save();

        return success();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): JsonResponse
    {
        if (!$this->user->system_operator) {
            return fail('没有操作权限');
        }

        $menu = Menu::find($request->input('id'));
        $menu->delete();

        return success();
    }
}
