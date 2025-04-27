<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $feedbacks = Feedback::where('store_id', $this->store_id)
            ->orderBy('created_at', 'desc')
            ->limit(30)->get();

        return success($feedbacks);
    }

    public function form(Request $request): JsonResponse
    {
        if (Feedback::where('store_id', $this->store_id)->where('status', 0)->count() > 10)
            return fail('提交次数过多，请稍后再试');

        $contents = $request->input('contents');
        if (empty($contents)) return fail('请输入反馈内容');

        Feedback::create([
            'store_id' => $this->store_id,
            'content' => $contents,
            'images' => $request->input('images'),
        ]);

        return success();
    }
}
