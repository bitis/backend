<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>

    <style>
        .table_row {
            height: 45px;
        }

        .table_row td {
            border: 1px solid #000;
        }

        .last_row td {
            border-bottom: none;
        }

        .table_box {
            font-size: 17px;
            border-collapse: collapse;
        }

        .table_label {
            width: 15%;
        }

        .table_value {
            width: 35%;
        }

        th {
            border: 1px solid #000;
            color: #0274fe;
        }

        * {
            font-weight: 800;
        }
    </style>
</head>

<body>
<div id="pdfRef" style="position: relative">
    <div>
        <div style="
              padding-left: 20px;
              padding-top:40px;
            ">
            <img crossorigin="anonymous"
                 src="@if($quotation->company->logo){{ config('filesystems.disks.qcloud.url') . $quotation->company->logo }}@endif"
                 alt="水印" style="width: 80px; height: 80px;margin-right: 20px;"/>
            <div style="width: 60%;position: absolute;
                left:120px;
                top: 40px;">
                <div style="font-size: 25px;margin-top: 10px;">{{ $quotation->company->name }}</div>
                <div style="font-size: 30px;margin-top: 16px;">损失清单</div>
            </div>
        </div>
        <div style="text-align: center;position: absolute;
            top: 10px;
            right: 10px;">
            <img crossorigin="anonymous"
                 src="{{ config('filesystems.disks.qcloud.url') . $quotation->qrcode }}"
                 alt="防伪码" style="width: 100px; height: 100px"/>
            <div style="font-size: 20px;color: #333333;margin-top: 10px;">防伪码，微信扫一扫</div>
        </div>
    </div>
    <div style="height: 80px"></div>
    <table class="table_box" style="width: 100%; text-align: center">
        <tr class="table_row">
            <td colspan="1" class="table_label">报价时间</td>
            <td colspan="3" class="table_value">{{ $quotation->created_at }}</td>
            <td colspan="1" class="table_label">报价有效期</td>
            <td colspan="3" class="table_value">自报价之日起30日内有效</td>
        </tr>
        <tr class="table_row">
            <td colspan="1" class="table_label">客户</td>
            <td colspan="3" class="table_value">{{ $quotation->order->company->name }}</td>
            <td colspan="1" class="table_label">查勘人及电话</td>
            <td colspan="3"
                class="table_value">{{$quotation->order->wusun_check_name }} {{$quotation->order->wusun_check_phone }}</td>
        </tr>
        <tr class="table_row">
            <td class="table_label" style="width: 100px">报案号</td>
            <td colspan="5">{{ $quotation->order->case_number }}</td>
        </tr>
        <tr class="table_row">
            <td colspan="1" class="table_label">车牌号</td>
            <td colspan="3" class="table_value">{{ $quotation->order->license_plate }}</td>
            <td colspan="1" class="table_label">报价单位</td>
            <td colspan="3" class="table_value">{{ $quotation->company->name }}</td>
        </tr>
        <tr class="table_row last_row">
            <td colspan="1" class="table_label">物损地点</td>
            <td colspan="3"
                class="table_value">{{$quotation->order->province.$quotation->order->city.$quotation->order->area.$quotation->order->address }}</td>
            <td colspan="1" class="table_label">联系人及电话</td>
            <td colspan="3"
                class="table_value">{{ $creator->name }} {{ $creator->mobile }}</td>
        </tr>
    </table>
    <table class="table_box" style="width: 100%; text-align: center">
        <tr class="table_row">
            <th style="width: 5%">序号</th>
            <th style="width: 15%">项目名称</th>
            <th style="width: 20%">规格/型号</th>
            <th style="width: 10%">单位</th>
            <th style="width: 5%">数量</th>
            <th style="width: 10%">单价</th>
            <th style="width: 10%">总价</th>
            <th>备注</th>
        </tr>
        @foreach ($quotation->items as $item)
            <tr class="table_row">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->specs }}</td>
                <td>{{ $item->unit }}</td>
                <td>{{ $item->number }}</td>
                <td>{{ $item->price }}</td>
                <td>{{ $item->total_price }}</td>
                <td>{{ $item->remark }}</td>
            </tr>
        @endforeach
        <tr class="table_row" style="background: rgb(255, 255, 255)">
            <td colspan="2">总价合计</td>
            <td colspan="7">{{ $quotation->total_price }}元，大写：{{ number2chinese($quotation->total_price, true) }}</td>
        </tr>
        <tr class="table_row">
            <td colspan="2">报价备注</td>
            <td colspan="7">{{ $quotation->quotation_remark }}</td>
        </tr>
    </table>
    <div style="
            position: absolute;
            bottom: 20px;
            right: 20px;
          "><img crossorigin="anonymous"
                 src="@if($quotation->company->official_seal){{ config('filesystems.disks.qcloud.url') . $quotation->company->official_seal }}@endif"
                 alt="印章" style="width: 120px; height: 120px"/></div>
</div>
</body>

</html>
