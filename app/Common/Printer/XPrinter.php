<?php

namespace App\Common\Printer;

use GuzzleHttp\Client;
use App\Exceptions\PrinterException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

/**
 * 鑫烨云打印机
 *
 * @see https://www.xprinter.net/companyfile/47.html
 *
 * @method array addPrinters(...$params)
 * @method array setVoiceType(...$params)
 * @method array print(...$params)
 * @method array printLabel(...$params)
 * @method array delPrinters(...$params)
 * @method array updPrinter(...$params)
 * @method array delPrinterQueue(...$params)
 * @method array queryOrderState(...$params)
 * @method array queryOrderStatis(...$params)
 * @method array queryPrinterStatus(...$params)
 * @method array queryPrintersStatus(...$params)
 * @method array playVoice(...$params)
 * @method array pos(...$params)
 * @method array controlBox(...$params)
 * @method array playVoiceExt(...$params)
 * @method array playCustomVoice(...$params)
 * @method array uploadLogo(...$params)
 * @method array delUploadLogo(...$params)
 * @method array printerInfo(...$params)
 *
 * @throws PrinterException
 */
class XPrinter
{
    protected string $baseUrl = 'https://open.xpyun.net';

    protected array $errorInfo = [
        '0' => '成功',
        '-1' => '请求头错误',
        '-2' => '参数不合法',
        '-3' => '参数签名失败',
        '-4' => '用户未注册',
        '1001' => '打印机编号和用户不匹配',
        '1002' => '打印机未注册',
        '1003' => '打印机不在线',
        '1004' => '添加订单失败',
        '1005' => '未找到订单信息',
        '1006' => '订单日期格式或大小不正确',
        '1007' => '打印内容不能超过12K',
        '1008' => '用户修改打印机记录失败',
        '1009' => '用户添加打印机时，打印机编号或名称不能为空',
        '1010' => '打印机设备编号无效',
        '1011' => '打印机已存在，若当前开放平台无法查询到打印机信息，请联系售后技术支持人员核实',
        '1012' => '添加打印设备失败，请稍后再试或联系售后技术支持人员',
        '1013' => '打印订单时触发幂等性',
        '1014' => '幂等因子过长',
        '1016' => 'LOGO文件格式错误',
        '1017' => 'LOGO文件超出规定范围',
        '1018' => 'LOGO上传次数超限制',
        '1020' => 'LOGO删除失败',
        '1021' => 'LOGO上传模式错误'
    ];

    /**
     * @var Client
     */
    private Client $httpClient;

    protected array $config;

    public function __construct()
    {
        $this->config = config('printer.xprinter');
    }

    /**
     * @return Client
     */
    private function getHttpClient(): Client
    {
        if (empty($this->httpClient)) {
            $this->httpClient = new Client([
                'base_uri' => $this->baseUrl,
            ]);
        }

        return $this->httpClient;
    }

    private function parsePath($functionName): string
    {
        return 'api/openapi/xprinter/' . $functionName;
    }

    private function defaultParams(): array
    {
        return [
            'user' => $this->config['user'],
            'timestamp' => time(),
            'sign' => sha1($this->config['user'] . $this->config['key'] . time()),
            'debug' => $this->config['debug'],
        ];
    }

    /**
     * @throws GuzzleException|PrinterException
     */
    public function postJson($path, $data = []): array|string|null
    {
        $response = $this->getHttpClient()->request('POST', $this->parsePath($path), ['json' => array_merge($data, $this->defaultParams())]);

        $result = json_decode($response->getBody()->getContents(), true);

        Log::debug('XPRINTER', $result);

        if ($result['code'] != 0) {
            throw new PrinterException($this->errorInfo[$result['code']], $result['code']);
        }

        return $result['data'];
    }

    /**
     * @throws GuzzleException
     * @throws PrinterException
     */
    public function __call(string $name, array $arguments)
    {
        return $this->postJson($name, $arguments);
    }
}
