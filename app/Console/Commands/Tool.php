<?php

namespace App\Console\Commands;

use App\Common\Printer\Format\Format58;
use App\Common\Printer\XPrinter;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class Tool extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tool';

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
    }
}
