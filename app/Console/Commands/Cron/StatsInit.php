<?php

namespace App\Console\Commands\Cron;

use App\Models\Store;
use App\Models\StoreStat;
use Illuminate\Console\Command;

class StatsInit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:stats-init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '统计初始化';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Store::orderBy('id', 'asc')->chunk(100, function ($stores) {
            foreach ($stores as $store) {
                StoreStat::findOrCreate(['store_id' => $store->id, 'date' => date('Y-m-d')], [
                    'month' => date('Ym'),
                    'year' => date('Y'),
                ]);
            }
        });
    }
}
