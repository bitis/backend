<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function form(Request $request): JsonResponse
    {
        $file = $request->file('file');

        if (!$file) return fail('必须上传一个文件');

        $ext = $file->getClientOriginalExtension();

        $fileName = '/uploads/' . date('Ymd') . '/' . Str::random(40) . ($ext ? '.' . $ext : '');

        if (Storage::disk('qcloud')->put($fileName, $file->getContent()))
            return success($fileName);

        return fail('上传失败');
    }
}
