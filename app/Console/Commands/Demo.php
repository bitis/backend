<?php

namespace App\Console\Commands;

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
    public function handle(XPrinter $printer)
    {
        $printer->printerInfo(sn:Str::random(16));
    }

    function demo(...$params)
    {
        dd($params);
    }
}
