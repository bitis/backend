<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\BalanceTransaction;
use App\Models\CloudFile;
use App\Models\Member;
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

        $members = Member::with('level')
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
        $member = Member::with('level')->find($request->input('id'));

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
        $config = OfficialAccountConfig::find($this->store()->official_account_id);
        $app = Factory::officialAccount($config);
        $result = $app->qrcode->temporary(json_encode([
            'k' => 'bind_user',
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
}
