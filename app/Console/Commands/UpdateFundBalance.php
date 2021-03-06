<?php

namespace App\Console\Commands;

use App\Fund;
use App\FundBalanceHistory;
use App\Library\BinanceHelper;
use App\Library\BittrexHelper;
use App\Library\BlockchainHelper;
use App\Library\CoinMarketCapHelper;
use App\Library\CryptoPrice;
use App\Library\EtherScanHelper;
use App\User;
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

    protected $coins = [];

    /**
     * @var Fund
     */
    protected $fund;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        // Init fund
        $this->fund = Fund::where('slug', 'tothemoon')->first();
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

        // Statistic
        $this->fund->capital_market = $this->balance_usd;

        $this->get_btc_wallet_cash();
        $this->get_eth_wallet_cash();

        if ($this->balance_btc > 0 && $this->balance_usd > 0) {
            $this->update_fund_balance($this->balance_btc, $this->balance_usd);
            echo 'Real balance at ' . Carbon::now()->toDateTimeString() . ' equal to ' . round($this->balance_usd, 2) . '$' . PHP_EOL;
        }
    }

    /**
     * Calculate price of coins added manually from admin part.
     *
     * @return float|int
     */
    private function calculate_manual_coins()
    {
        if (!empty($this->fund)) {
            $coins_price = 0;
            foreach ($this->fund->coins as $coin) {
                if ($coin->amount > 0) {
                    $coins_price += $coin->amount * CoinMarketCapHelper::price($coin->symbol);
                    // Save for history
                    $this->add_to_coin_array($coin->symbol, $coin->amount);
                }
            }
            echo 'Manual added coins value equal to ' . round($coins_price, 2) . '$' . PHP_EOL;
        }

        return empty($coins_price) ? 0 : $coins_price;
    }

    /**
     * Fetch an amount of BTC on wallet provided in .env.
     */
    private function get_btc_wallet_cash()
    {
        $data = BlockchainHelper::get_transactions(config('app.BTC_ADDRESS'));
        if (!empty($data) && !empty($data['final_balance'])) {
            $btc_eq = $data['final_balance'] / 100000000;
            $usd_amount = CryptoPrice::convert($btc_eq, 'btc', 'usd');
            $this->balance_btc += $btc_eq;
            $this->balance_usd += $usd_amount;
            // Save for history
            $this->add_to_coin_array('btc', $btc_eq);
            $this->fund->capital_blockchain = $usd_amount;
            // debug
            echo 'BTC wallet amount equal to ' . round($usd_amount, 2) . '$' . PHP_EOL;
        }
    }

    /**
     * Fetch an amount of ETH on wallet provided in .env.
     */
    private function get_eth_wallet_cash()
    {
        $eth_balance = EtherScanHelper::get_wallet_balance(config('app.ETH_ADDRESS'));
        if (!empty($eth_balance)) {
            $btc_amount = BittrexHelper::convert_to_btc($eth_balance, 'ETH');
            $usd_amount = CryptoPrice::convert($btc_amount, 'btc', 'usd');
            $this->balance_btc += $btc_amount;
            $this->balance_usd += $usd_amount;
            // Save for history
            $this->add_to_coin_array('eth', $eth_balance);
            $this->fund->capital_etherscan = $usd_amount;
            // debug
            echo 'ETH wallet amount equal to ' . round($usd_amount, 2) . '$' . PHP_EOL;
        }
    }

    /**
     * Calculate BTC and USD equivalent of coins on Bittrex.
     *
     * @return array
     */
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
                    $currency = $account['Currency'];
                    $coin_amount = $account['Balance'];

                    if ($currency == 'BTC') {
                        $btc_eq = $coin_amount;
                    } else {
                        $btc_eq = BittrexHelper::convert_to_btc($coin_amount, $currency);
                    }

                    if (!empty($btc_eq)) {
                        $amount = CryptoPrice::convert($btc_eq, 'btc', 'usd');
                        $balance_btc += $btc_eq;
                        $balance_usd += $amount;

                        // Save for history
                        $this->add_to_coin_array($currency, $coin_amount);
                    }
                }
            }
        }

        return [
            'btc' => empty($balance_btc) ? 0 : $balance_btc,
            'usd' => empty($balance_usd) ? 0 : $balance_usd,
        ];
    }

    /**
     * Calculate BTC and USD equivalent of coins on Binance.
     *
     * @return array
     */
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
                    $currency = $account['asset'];
                    $coin_amount = $account['free'];

                    if ($currency == 'BTC') {
                        $btc_eq = $coin_amount;
                    } else {
                        $btc_eq = BinanceHelper::convert_to_btc($coin_amount, $currency);
                    }

                    if (!empty($btc_eq)) {
                        $amount = CryptoPrice::convert($btc_eq, 'btc', 'usd');
                        $balance_btc += $btc_eq;
                        $balance_usd += $amount;

                        // Save for history
                        $this->add_to_coin_array($currency, $coin_amount);
                    }
                }
            }
        }

        return [
            'btc' => empty($balance_btc) ? 0 : $balance_btc,
            'usd' => empty($balance_usd) ? 0 : $balance_usd,
        ];
    }

    /**
     * Update fund balance and calculate token price.
     *
     * @param float $balance_btc
     * @param float $balance_usd
     */
    private function update_fund_balance(float $balance_btc, float $balance_usd)
    {
        if (!empty($this->fund) && $this->fund->token_count > 0) {
            // Calculate token count
            $token_count = User::all()->reduce(function ($carry, $user) {
                return $carry + $user->balance->body;
            });
            // Calculate manually added amounts
            $free_usd  = !empty($this->fund->manual_balance_usd) ? $this->fund->manual_balance_usd : 0;
            $this->add_to_coin_array('usd', $free_usd);
            $free_usd += $this->calculate_manual_coins();
            // Save balance
            $this->fund->balance_btc = $balance_btc + CryptoPrice::convert($free_usd, 'usd', 'btc');
            $this->fund->balance_usd = $balance_usd + $free_usd;
            // Subtract reserve amount
            $total_usd_amount = $balance_usd + $free_usd;
            $total_usd_amount -= $this->fund->reserve_usd;
            // Update token count and price
            $this->fund->token_count = $token_count;
            $this->fund->token_price = $total_usd_amount / $this->fund->token_count;
            $this->fund->save();
            // Save history
            $this->save_history();
        }
    }

    private function add_to_coin_array($symbol, $amount)
    {
        $symbol = strtolower($symbol);
        if (empty($this->coins[$symbol])) $this->coins[$symbol] = 0;
        $this->coins[$symbol] += $amount;
    }

    /**
     * Save coins amount.
     * Method doesn't save coin price because it's publicity available history chart data.
     */
    private function save_history()
    {
        $history = new FundBalanceHistory();
        $prev_entry = FundBalanceHistory::latest()->first();
        foreach ($this->coins as $symbol => $amount) {
            $history->$symbol = $amount;
        }
        $history->save();
        if (!empty($prev_entry)) {
            $history->previousEntry()->associate($prev_entry)->save();
        }
    }
}
