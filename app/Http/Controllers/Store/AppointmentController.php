<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentConfig;
use App\Models\Member;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    /**
     * 列表 7217
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $appointments = Appointment::with('service')
            ->where('store_id', $this->store_id)
            ->when($request->input('member_id'), fn($query, $member_id) => $query->where('member_id', $member_id))
            ->when($request->input('status'), fn($query, $status) => $query->where('status', $status))
            ->when($request->input('start_time'), fn($query, $start_time) => $query->where('datetime', '>=', $start_time))
            ->when($request->input('end_time'), fn($query, $end_time) => $query->where('datetime', '<', $end_time))
            ->when($request->input('search'), fn($query, $search) => $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")->orWhere('mobile', 'like', "%{$search}%");
            }))
            ->orderBy('datetime')
            ->paginate(getPerPage());

        return success($appointments);
    }

    /**
     * 可预约项目
     *
     * @return JsonResponse
     */
    public function services(): JsonResponse
    {
        $services = DB::table((new Product())->getTable())
            ->where('store_id', $this->store_id)
            ->where('type', Product::TYPE_SERVICE)
            ->limit(50)
            ->selectRaw('name as label, id as value')
            ->get();

        return success($services);
    }

    /**
     * 提交预约
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function form(Request $request): JsonResponse
    {
        $appointment = Appointment::where('store_id', $this->store_id)
            ->findOr($request->input('id'), fn() => new Appointment(['store_id' => $this->store_id]));

        $appointment->fill($request->only([
            'name',
            'mobile',
            'product_id',
            'datetime',
            'number',
            'remark',
        ]));

        $member = Member::where('store_id', $this->store_id)->where('mobile', $request->input('mobile'))->first();
        if ($member) $appointment->member_id = $member->id;

        $product = Product::where('store_id', $this->store_id)->where('id', $request->input('product_id'))->first();
        if ($product) $appointment->product_name = $product->name;

        $appointment->save();

        return success();
    }

    /**
     * 详情
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function detail(Request $request): JsonResponse
    {
        $appointment = Appointment::where('store_id', $this->store_id)->find($request->input('id'));

        return success($appointment);
    }

    /**
     * 改变状态
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function setStatus(Request $request): JsonResponse
    {
        $status = $request->input('status');

        $at = match ($status) {
            Appointment::status_submit, Appointment::status_timeout => [],
            Appointment::status_confirm => ['confirm_at' => now()],
            Appointment::status_checkin => ['checkin_at' => now()],
            Appointment::status_cancel => ['cancel_at' => now()]
        };

        Appointment::where('store_id', $this->store_id)
            ->where('id', $request->input('id'))
            ->update(array_merge(['status' => $request->input('status')], $at));

        return success();
    }

    /**
     * 获取预约配置
     *
     * @return JsonResponse
     */
    public function config(): JsonResponse
    {
        return success(AppointmentConfig::where('store_id', $this->store_id)->first());
    }

    /**
     * 预约配置
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function configForm(Request $request): JsonResponse
    {
        AppointmentConfig::updateOrCreate(['store_id' => $this->store_id],
            $request->only([
                'earliest',
                'latest',
                'interval',
                'max_number',
                'before_time',
                'slots',
                'status',
            ])
        );

        return success();
    }

}
