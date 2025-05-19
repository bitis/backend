<?php

namespace App\Http\Controllers\Store;

use App\Common\Filter;
use App\Http\Controllers\Controller;
use App\Models\BalanceTransaction;
use App\Models\CloudFile;
use App\Models\Grade;
use App\Models\Member;
use App\Models\MemberCard;
use App\Models\OfficialAccountConfig;
use EasyWeChat\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MemberController extends Controller
{
    /**
     * 会员列表
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $words = $request->input('words');

        $filters = $request->input('filters');

        foreach ($filters as $filter) {
            match ($filter->key) {
                'grade_id' => ['grade_id', '=', $filter->value],
                'sleep' => ['last_consume_at', '<=', now()->addMonths($filter->value * -1)],
                'birthday' => Filter::birthday($filter->value)
            };
        }

        $members = Member::with('grade')
            ->where('store_id', $this->store_id)
            ->whereRaw()
            ->when($words, function ($query, $words) {
                $query->where('mobile', 'like', "%$words%")
                    ->orWhere('name', 'like', "%$words%");
            })
            ->paginate(getPerPage());

        return success($members);
    }

    /**
     * 创建、修改
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function form(Request $request): JsonResponse
    {
        $member = Member::where('store_id', $this->store_id)
            ->findOr(request('id'), fn() => new Member(['store_id' => $this->store_id]));

        $member->fill($request->all());

        $member->save();

        return success();
    }

    /**
     * 删除
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        Member::where('store_id', $this->store_id)->where('id', $request->input('id'))->delete();

        return success();
    }

    /**
     * 会员详情
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function detail(Request $request): JsonResponse
    {
        $member = Member::with('grade')->find($request->input('id'));

        $member->card_count = MemberCard::where('member_id', $member->id)->count();

        return success($member);
    }

    /**
     * 上传头像
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function avatar(Request $request): JsonResponse
    {
        $member = Member::where('store_id', $this->store_id)->find($request->input('id'));

        $file = $request->file('file');

        if (!$file) return fail('必须上传一个文件');

        $ext = $file->getClientOriginalExtension();

        $fileName = '/uploads/' . date('Ym') . '/avatars/' . Str::random(40) . ($ext ? '.' . $ext : '');

        if (Storage::disk('qcloud')->put($fileName, $file->getContent())) {
            CloudFile::create([
                'name' => $file->getClientOriginalName(),
                'path' => $fileName,
                'size' => $file->getSize(),
                'type' => $file->getMimeType(),
                'user_id' => $request->user()->id,
            ]);

            $member->avatar = $fileName;
            $member->save();

            return success($fileName);
        }

        return fail();
    }

    /**
     * 微信绑定二维码
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function qrcode(Request $request): JsonResponse
    {
        $config = OfficialAccountConfig::find($this->store()->official_account_id)->toArray();
        $app = Factory::officialAccount($config);
        $result = $app->qrcode->temporary(json_encode([
            'k' => 'member-bind',
            'v' => $request->input('id')
        ]), 2592000);

        return success([
            'url' => $app->qrcode->url($result["ticket"])
        ]);
    }

    /**
     * 余额流水
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function transaction(Request $request): JsonResponse
    {
        $transactions = BalanceTransaction::with('operator:id,name,avatar')
            ->where('member_id', $request->input('member_id'))
            ->orderBy('id', 'desc')
            ->paginate(getPerPage());

        return success($transactions);
    }

    public function filters(): JsonResponse
    {
        $grades = [
            'name' => '会员等级',
            'key' => 'grade_id',
            'values' => Grade::where('store_id', $this->store_id)->select(['id', 'name'])->get()->toArray()
        ];

        $sleep = [
            'name' => '沉睡会员',
            'key' => 'sleep',
            'values' => [
                ['id' => '1', 'name' => '一个月内未消费'],
                ['id' => '3', 'name' => '三个月内未消费'],
                ['id' => '6', 'name' => '六个月内未消费'],
                ['id' => '12', 'name' => '一年内未消费'],
            ]
        ];

        $birthday = [
            'name' => '生日',
            'key' => 'birthday',
            'values' => [
                ['id' => '1', 'name' => '今天'],
                ['id' => '2', 'name' => '最近7天'],
                ['id' => '3', 'name' => '当月'],
            ]
        ];

        return success([$grades, $sleep, $birthday]);
    }
}
