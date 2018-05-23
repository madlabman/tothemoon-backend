<?php

namespace App\Console\Commands;

use App\Fund;
use App\Profit;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GetDayProfitValue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tothemoon:profit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compute today profit value.';

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
     *
     * @return mixed
     */
    public function handle()
    {
        $fund = Fund::where('slug', '=', 'tothemoon')->first();
        if (!empty($fund)) {
            // Getting last profit data
            $last_profit = $fund->profits()->latest()->first();
            if (!empty($last_profit)) {
                // Skip if profit created same day
                $today = Carbon::createMidnightDate();
                $last_profit_day = Carbon::createMidnightDate(
                    $last_profit->created_at->year,
                    $last_profit->created_at->month,
                    $last_profit->created_at->day
                );
                if ($today == $last_profit_day) exit;
                // Getting balance diff
                $current_price = $fund->token_price;
                $last_price = $last_profit->token_price;
                if ($last_price > 0) {
                    // token
                    $change = $current_price - $last_price;
                    $change_percent = $change / $last_price;
                    // btc
                    $btc_change = $fund->balance_btc * $change_percent;
                    // usd
                    $usd_change = $fund->balance_usd * $change_percent;
                    // Store profit value
                    $profit = Profit::create([
                        'token_change'          => $change,
                        'token_change_percent'  => $change_percent,
                        'token_price'           => $current_price,
                        'btc_change'            => $btc_change,
                        'usd_change'            => $usd_change,
                        'balance'               => $fund->balance_usd,
                    ]);
                    $profit->fund()->associate($fund)->save();
                }
            } else {
                $current_price = $fund->token_price;
                // Create new profit value
                $profit = Profit::create([
                    'token_price'           => $current_price,
                    'balance'               => $fund->balance_usd,
                ]);
                $profit->fund()->associate($fund)->save();
            }
        }
    }
}
