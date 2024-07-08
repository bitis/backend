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

        unset($menu);

        $result = [];

        foreach ($menus as $_menu) {
            if ($_menu['parent_id'] == 0) {
                $folder = array_merge($_menu, ['children' => [], 'meta' => [
                    'requiresAuth' => true,
                    'icon' => $_menu['icon'],
                    'order' => $_menu['sort'],
                    'hideInMenu' => false
                ]]);
                foreach ($menus as $__menu) {
                    if ($__menu['parent_id'] == $_menu['id']) {
                        $menu = array_merge($__menu, ['children' => [], 'meta' => [
                            'requiresAuth' => true,
                            'icon' => $__menu['icon'],
                            'order' => $__menu['sort'],
                            'hideInMenu' => false
                        ]]);
                        foreach ($menus as $___menu) {
                            if ($___menu['parent_id'] == $__menu['id']) {
                                $___menu['meta'] = [
                                    'requiresAuth' => true,
                                    'icon' => $___menu['icon'],
                                    'order' => $___menu['sort'],
                                    'hideInMenu' => false
                                ];
                                $menu['children'][] = $___menu;
                            }
                        }
                        $folder['children'][] = $menu;
                    }
                }

                $result[] = $folder;
            }
        }

        return success($result);
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
