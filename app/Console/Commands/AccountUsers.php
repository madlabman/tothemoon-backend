<?php

namespace App\Console\Commands;

use App\Fund;
use App\LevelCondition;
use App\Profit;
use App\Repository\UserRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AccountUsers extends Command
{
    /**
     * @var Fund|\Illuminate\Database\Eloquent\Model|null|object
     */
    protected $fund;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tothemoon:accounting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check users and calculate their profit';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->fund = Fund::where('slug', 'tothemoon')->first();
        if (empty($this->fund)) return;
        $this->userRepository = $userRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = $this->userRepository->users_need_for_accounting();
        if (empty($users)) return false;

        foreach ($users as $user) {
            $this->account($user);
        }

        return true;
    }

    protected function account(User $user)
    {
        $body_in_usd = $this->fund->token_price * $user->balance->body;
        if ($body_in_usd < $user->balance->primary_usd) return;
        // Getting last account date
        $last_account_date = empty($user->last_accounted_at) ? $user->invested_at : $user->last_accounted_at;

        // Getting token price values
        $price_data = Profit::where('created_at', '<', $last_account_date)
            ->orderBy('created_at', 'desc')
            ->first();
        if (empty($price_data)) return;                                     // HALT. Cannot retrieve last price.

        $last_price = $price_data->token_price;
        $current_price = $this->fund->token_price;

        if ($current_price <= $last_price) return;                          // HALT. Profit less or equal to zero.
        $profit = $user->balance->body * ($current_price - $last_price);

        // Calculate user and fund profit
        $invest_level = LevelCondition::find($user->invest_level);
        if (empty($invest_level)) return;                                   // HALT. Undefined investment level.

        $user_profit = $profit * $invest_level->investor_pie;
        $fund_profit = $profit - $user_profit;

        // Update user balance
        $user->balance->body += $user->balance->bonus;
        $user->balance->bonus = $user_profit / $current_price;
        $user->balance->save();
        // Save account date
        $user->last_accounted_at = Carbon::now();
        $user->save();

        // Fund profit to referral chain
        $referral_pie = [
            1   => 0.10,
            2   => 0.05,
            3   => 0.03,
            4   => 0.02,
            5   => 0.01,
        ];
        // Getting referrals chain 1 -> 5
        $chain = $this->userRepository->referral_chain($user->login);
        foreach ($chain as $row) {
            echo $row['n.login'] . " -> " . $row['c'] . PHP_EOL;  // debug output
            // Getting referral profit
            $referral = User::where('login', $row['n.login'])->first();
            if (!empty($referral)) {
                $referral_profit = $fund_profit * $referral_pie[$row['c']];
                $referral->balance->bonus += $referral_profit;
                $referral->balance->save();
                // Decrease fund profit
                $fund_profit -= $referral_profit;
            }
        }

        // Fund profit to fund
        $this->fund->token_count += $fund_profit;
        $this->fund->save();
        // Fund profit to fund owners
        $owner_login_array = [
            'osipov',
            'farmorg',
        ];
        foreach ($owner_login_array as $owner) {
            $owner_user = User::where('login', $owner)->first();
            if (!empty($owner_user)) {
                $owner_user->balance->body += $fund_profit / count($owner_login_array);
                $owner_user->balance->save();
            }
        }
    }
}
