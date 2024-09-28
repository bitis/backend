<?php

namespace App\Console\Commands\Cron;

use App\Models\MemberCard;
use Illuminate\Console\Command;

class CardValid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:card-valid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '会员卡失效更新';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        MemberCard::where('valid', 1)->where('valid_type', 2)->where('valid_time', '<', now())->update(['valid' => 0]);
    }
}
