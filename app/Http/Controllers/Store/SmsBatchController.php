<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\CloudFile;
use App\Models\SmsDetail;
use App\Models\SmsRecord;
use App\Models\SmsSignature;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\DefaultValueResolver;

class SmsBatchController extends Controller
{
    /**
     * 提交记录
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function history(Request $request): JsonResponse
    {
        $records = SmsRecord::where('store_id', $this->store_id)
            ->when($request->input('title'),
                fn($query, $title) => $query->where('title', 'like', '%' . $title . '%')
            )
            ->when($request->input('created_at'),
                fn($query, $title) => $query->where('created_at', '>', strtotime($title[0]))
                    ->where('created_at', '<', strtotime($title[1] . ' 23:59:59'))
            )
            ->paginate(getPerPage());
        return success($records);
    }

    /**
     * 发送短信
     */
    public function form(Request $request): JsonResponse
    {
        $content = $request->input('content');
        $signature = $request->input('signature');
        $mobiles = $request->input('mobiles');
        $import_mobiles = [];

        if ($file = $request->input('file')) {
            $temp = Storage::disk()->get($file);
        }

        try {
            DB::beginTransaction();
            $record = SmsRecord::create([
                'store_id' => $this->store_id,
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'signature' => $signature,
                'file' => $file ?? null,
                'content_length' => mb_strlen($signature) + mb_strlen($content) + 7,
                'mobile_count' => count($mobiles) + count($import_mobiles),
            ]);

            foreach ($mobiles as $mobile) {
                SmsDetail::create([
                    'store_id' => $this->store_id,
                    'sms_record_id' => $record->id,
                    'mobile' => $mobile,
                    'source' => 1,
                    'content' => $signature . $request->input('content') . "，拒收请回复R",
                ]);
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        return success($record);
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
     * 发送明细
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function detailRecord(Request $request): JsonResponse
    {
        $record = SmsDetail::where('store_id', $this->store_id)
            ->when($request->input('record_id'),
                fn($query, $record_id) => $query->where('sms_record_id', $record_id)
            )
            ->when($request->input('mobile'),
                fn($query, $mobile) => $query->where('mobile', 'like', '%' . $mobile . '%')
            )
            ->when($request->input('created_at'),
                fn($query, $title) => $query->where('created_at', '>', strtotime($title[0]))
                    ->where('created_at', '<', strtotime($title[1] . ' 23:59:59'))
            )
            ->paginate(getPerPage());

        return success($record);
    }

    public function createSignature(Request $request): JsonResponse
    {
        $name = $request->input('name');

        if (SmsSignature::where('store_id', $this->store_id)->where('name', $name)->exists())
            return fail('短信签名已存在');

        SmsSignature::create([
            'store_id' => $this->store_id,
            'name' => $name
        ]);

        return success();
    }

    public function getSignatures(): JsonResponse
    {
        return success(SmsSignature::where('store_id', $this->store_id)->get());
    }
}
