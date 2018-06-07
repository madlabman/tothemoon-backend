<?php

namespace App\Console\Commands;

use App\Fund;
use App\Library\BinanceHelper;
use App\Library\BittrexHelper;
use App\Library\BlockchainHelper;
use App\Library\CoinMarketCapHelper;
use App\Library\CryptoPrice;
use App\Library\EtherScanHelper;
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

    protected $balance_usd = 0;

    protected $balance_btc = 0;

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
        $bittrex_balance = $this->get_bittrex_balance();
        $binance_balance = $this->get_binance_balance();

        $this->balance_usd += $bittrex_balance['usd'] > 0 ? $bittrex_balance['usd'] : 0;
        $this->balance_btc += $bittrex_balance['btc'] > 0 ? $bittrex_balance['btc'] : 0;

        $this->balance_usd += $binance_balance['usd'] > 0 ? $binance_balance['usd'] : 0;
        $this->balance_btc += $binance_balance['btc'] > 0 ? $binance_balance['btc'] : 0;

        $this->get_btc_wallet_cash();
        $this->get_eth_wallet_cash();

        if ($this->balance_btc > 0 && $this->balance_usd > 0) {
            $this->update_fund_balance($this->balance_btc, $this->balance_usd);
            echo 'Real balance at ' . Carbon::now()->toDateTimeString() . ' equal to ' . $this->balance_usd . '$' . PHP_EOL;
        }
    }

    private function calculate_manual_coins()
    {
        $fund = Fund::where('slug', 'tothemoon')->first();
        if (!empty($fund)) {
            $coins_price = 0;
            foreach ($fund->coins as $coin) {
                if ($coin->amount > 0) {
                    $coins_price += $coin->amount * CoinMarketCapHelper::price($coin->sym);
                }
            }
            echo 'Manual added coins value equal to ' . $coins_price . '$' . PHP_EOL;
        }

        return empty($coins_price) ? 0 : $coins_price;
    }

    private function get_btc_wallet_cash()
    {
        $data = BlockchainHelper::get_transactions(config('app.BTC_ADDRESS'));
        if (!empty($data) && !empty($data['final_balance'])) {
            $btc_eq = $data['final_balance'] / 100000000;
            $amount = CryptoPrice::convert($btc_eq, 'btc', 'usd');
            $this->balance_btc += $btc_eq;
            $this->balance_usd += $amount;
            // debug
            echo 'BTC wallet amount equal to ' . $amount . '$' . PHP_EOL;
        }
    }

    private function get_eth_wallet_cash()
    {
        $eth_balance = EtherScanHelper::get_wallet_balance(config('app.ETH_ADDRESS'));
        if (!empty($eth_balance)) {
            $btc_amount = BittrexHelper::convert_to_btc($eth_balance, 'ETH');
            $usd_amount = CryptoPrice::convert($btc_amount, 'btc', 'usd');
            $this->balance_btc += $btc_amount;
            $this->balance_usd += $usd_amount;
            // debug
            echo 'ETH wallet amount equal to ' . $usd_amount . '$' . PHP_EOL;
        }
    }

    private function get_bittrex_balance()
    {
        $acc_list = BittrexHelper::get_balances();
        if (!empty($acc_list) && $acc_list['success']) {
            $balance_usd = 0;
            $balance_btc = 0;
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
                        $balance_btc += $btc_eq;
                        $balance_usd += $amount;
                    }
                }
            }
        }

        return [
            'btc' => empty($balance_btc) ? 0 : $balance_btc,
            'usd' => empty($balance_usd) ? 0 : $balance_usd,
        ];
    }

    private function get_binance_balance()
    {
        $acc_list = BinanceHelper::get_balances();
        if (!empty($acc_list) && !empty($acc_list['balances'])) {
            $balance_usd = 0;
            $balance_btc = 0;
            // Go through account list
            foreach ($acc_list['balances'] as $account) {
                // Check only positive balanced accounts
                if ($account['free'] > 0) {
                    if ($account['asset'] == 'BTC') {
                        $btc_eq = $account['free'];
                    } else {
                        $btc_eq = BinanceHelper::convert_to_btc($account['free'], $account['asset']);
                    }

                    if (!empty($btc_eq)) {
                        $amount = CryptoPrice::convert($btc_eq, 'btc', 'usd');
                        $balance_btc += $btc_eq;
                        $balance_usd += $amount;
                    }
                }
            }
        }

        return [
            'btc' => empty($balance_btc) ? 0 : $balance_btc,
            'usd' => empty($balance_usd) ? 0 : $balance_usd,
        ];
    }

    private function update_fund_balance(float $balance_btc, float $balance_usd)
    {
        $fund = Fund::where('slug', 'tothemoon')->first();
        if (!empty($fund) && $fund->token_count > 0) {
            // Calculate manually added amounts
            $free_usd  = !empty($fund->manual_balance_usd) ? $fund->manual_balance_usd : 0;
            $free_usd += $this->calculate_manual_coins();
            // Save balance
            $fund->balance_btc = $balance_btc;
            $fund->balance_usd = $balance_usd + $free_usd;
            $fund->token_price = ($balance_usd + $free_usd) / $fund->token_count;
            $fund->save();
        }
    }
}
