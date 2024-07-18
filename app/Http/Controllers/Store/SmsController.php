<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\CloudFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SmsController extends Controller
{
    /**
     * 提交记录
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return success();
    }

    /**
     * 发送短信
     */
    public function form(Request $request): JsonResponse
    {
        if ($file = $request->input('file')) {
            $temp = Storage::disk()->get($file);

        }
        return success();
    }

    /**
     * 批量上传号码
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function upload(Request $request): JsonResponse
    {
        $file = $request->file('file');

        if (!$file) return fail('必须上传一个文件');

        $ext = $file->getClientOriginalExtension();

        $fileName = '/uploads/' . date('Ym') . '/sms/' . Str::random(40) . ($ext ? '.' . $ext : '');

        if (Storage::disk('qcloud')->put($fileName, $file->getContent())) {
            CloudFile::create([
                'name' => $file->getClientOriginalName(),
                'path' => $fileName,
                'size' => $file->getSize(),
                'type' => $file->getMimeType(),
                'user_id' => $request->user()->id,
            ]);

            return success($fileName);
        }

        return fail('上传失败');
    }

    /**
     * 发送详情
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function detail(Request $request): JsonResponse
    {
        $file = $request->file('file');

        if (!$file) return fail('必须上传一个文件');

        $ext = $file->getClientOriginalExtension();

        $fileName = '/uploads/' . date('Ym') . '/sms/' . Str::random(40) . ($ext ? '.' . $ext : '');

        if (Storage::disk('qcloud')->put($fileName, $file->getContent())) {
            CloudFile::create([
                'name' => $file->getClientOriginalName(),
                'path' => $fileName,
                'size' => $file->getSize(),
                'type' => $file->getMimeType(),
                'user_id' => $request->user()->id,
            ]);

            return success($fileName);
        }

        return fail('上传失败');
    }
}
