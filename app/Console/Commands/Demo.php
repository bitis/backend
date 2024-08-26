<?php

namespace App\Console\Commands;

use App\Common\Printer\Format\Format58;
use App\Common\Printer\XPrinter;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class Demo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(XPrinter $xPrinter)
    {
        $format = new Format58('哈哈哈哈', [
            [
                'name' => '一二三四五六七八九十一二三四五六七八九十',
                'number' => 999,
                'price' => 1999.99
            ],
            [
                'name' => '测试商品2',
                'number' => 2,
                'price' => 2
            ]
        ], [
            '一二三四五六七八九十一二三四五六七八九十一二三四五六七八九十一二三四五六七八九十',
            '一二三四五六七八九十一二三四五六七八九十一二三四五六七八九十一二三四五六七八九十'
        ], 'http://weixin.qq.com/q/02XcDhZvKZe-210000M07h', '*');

        $this->info($format);

        $xPrinter->print(sn:'74T9XNS9KEA3F4A', content: $format->toString());
    }
}
