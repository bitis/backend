<?php

namespace App\Http\Controllers;

use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Common\Version;
use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    public function gen(Request $request)
    {
        $opt = new QROptions([
            'version' => Version::AUTO,
            'outputType' => QROutputInterface::GDIMAGE_PNG,
            'eccLevel' => EccLevel::L,
            'imageBase64' => false,
            'bgColor' => [200, 150, 200],
            "scale" => 10,
        ]);
        $file = (new QRCode($opt))->render(urldecode($request->input('code')));

        return response($file)
            ->withHeaders([
                'Content-Type' => 'image/png',
                'Content-Disposition' => 'attachment; filename=qrcode.png',
            ]);
    }

}
