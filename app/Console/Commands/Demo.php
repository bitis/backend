<?php

namespace App\Console\Commands;

use App\Common\Printer\XPrinter;
use App\Models\WeBankStockRate;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Topsdk\Topapi\TopApiClient;

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

    protected Client $client;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $rates = WeBankStockRate::where('prod_code', '2501240018')
            ->whereBetween('earnings_rate_date', [now()->addDays(-31)->toDateString(), today()->toDateString()])
            ->orderBy('earnings_rate_date', 'desc')
            ->get();

        dd($start = $rates->pop());

    }

    private function time()
    {
        $response = $this->client->get('https://ldp.creditcard.ecitic.com/citiccard/lottery-gateway-pay/get-server-time.do');

        $t = json_decode($response->getBody()->getContents(), true)['resultData']['timeMillis'];

        $this->info(date('Y-m-d H:i:s', $t / 1000) . ' ' . $t % 1000);

        return $t;
    }
}
