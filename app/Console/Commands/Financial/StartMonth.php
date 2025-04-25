<?php

namespace App\Console\Commands\Financial;

use App\Models\WeBankStock;
use Illuminate\Console\Command;

class StartMonth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'we-bank:start-month';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $stocks = WeBankStock::all();

        foreach ($stocks as $stock) {
            $stock->pre_month_increase_money = $stock->month_increase_money;
            $stock->month_increase_money = 0;
            $stock->save();
        }
    }
}
