<?php

namespace App\Http\Controllers\Store;

use App\Common\Printer\XPrinter;
use App\Exceptions\PrinterException;
use App\Http\Controllers\Controller;
use App\Models\Printer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PrinterController extends Controller
{
    public function index(): JsonResponse
    {
        $printers = Printer::where('store_id', $this->store_id)->all();
        return success($printers);
    }

    public function form(Request $request, XPrinter $xPrinter): JsonResponse
    {
        $id = $request->input('id');
        if ($id) {
            $printer = Printer::find($id);
            if (empty($printer)) return fail('打印机不存在');
        } else {
            $printer = new Printer([
                'store_id' => $this->store_id
            ]);
        }
        try {
            $printer->fill($request->only(['name', 'sn']));

            $results = $xPrinter->addPrinters(items: [
                ['sn' => $printer->sn, 'name' => $printer->name]
            ]);

            if (count($results['fail']) > 0) {
                return fail("添加失败：" . $results['failMsg']);
            }
            $info = $xPrinter->printerInfo(sn: $printer->sn);

            $printer->fill(Arr::only($info, ['version', 'type', 'cutter']));
            $printer->save();
        } catch (PrinterException $e) {
            return fail($e->getMessage());
        }

        return success();
    }

    public function destroy(Request $request): JsonResponse
    {
        Printer::where('store_id', $this->store_id)->where('id', $request->input('id'))->delete();

        return success();
    }
}
