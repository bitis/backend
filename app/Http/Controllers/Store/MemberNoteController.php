<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\MemberNote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MemberNoteController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $notes = MemberNote::where('store_id', $this->store_id)
            ->where('member_id', $request->input('id'))
            ->orderBy('id', 'desc')
            ->paginate(getPerPage());

        return success($notes);
    }

    public function form(Request $request): JsonResponse
    {
        MemberNote::create([
            'store_id' => $this->store_id,
            'member_id' => $request->input('member_id'),
            'content' => $request->input('content'),
            'images' => $request->input('images'),
        ]);

        return success();
    }

    public function destroy(): JsonResponse
    {
        MemberNote::where('store_id', $this->store_id)->where('id', request('id'))->delete();

        return success();
    }
}
