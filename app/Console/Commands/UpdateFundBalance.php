<?php

namespace App\Console\Commands;

use App\Fund;
use App\Library\BittrexHelper;
use App\Library\CryptoPrice;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateFundBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tothemoon:balance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update fund balance';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $acc_list = BittrexHelper::get_balances();
        if (!empty($acc_list) && $acc_list['success']) {
            $balance = 0;
            // Go through account list
            foreach ($acc_list['result'] as $account) {
                // Check only positive balanced accounts
                if ($account['Balance'] > 0) {
                    if ($account['Currency'] == 'BTC') {
                        $btc_eq = $account['Balance'];
                    } else {
                        $btc_eq = BittrexHelper::convert_to_btc($account['Balance'], $account['Currency']);
                    }

                    if (!empty($btc_eq)) {
                        $amount = CryptoPrice::convert($btc_eq, 'btc', 'usd');
                        $balance += $amount;
                    }
                }
            }
        }

        if (!empty($balance)) {
            $this->update_fund_balance($balance);
            echo 'Computed balance at ' . Carbon::now()->toDateTimeString() . ' equal ' . $balance . '$' . PHP_EOL;
        }
    }

    private function update_fund_balance(float $balance)
    {
        $fund = Fund::where('slug', 'tothemoon')->first();
        if (!empty($fund) && $fund->token_count > 0) {
            $fund->balance = $balance;
            $fund->token_price = $balance / $fund->token_count;
            $fund->save();
        }
    }
}
