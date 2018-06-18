<?php

use App\Fund;
use App\Library\CryptoPrice;
use App\User;
use Illuminate\Database\Seeder;

class TokensSeeder extends Seeder
{
    protected $fund;

    private function token_to_usd($amount)
    {
        return $this->fund->token_price * $amount;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->fund = Fund::where('slug', 'tothemoon')->first();
        if (empty($this->fund)) return;

        // Balance of users in token by login
        $balance_matrix = [
            'cherkasov'     => 1391.81,
            'lopatin'       => 204.39,
            'yastrebov'     => 1134.20,
            'kuznetsov'     => 1796.99,
            'platonov'      => 1440.84,
            'potapova'      => 1094.90,
            'malyshev'      => 361.24,
            'berezin'       => 1801.59,
            'savanin'       => 7805.95,
            'tretyak'       => 697.84,
            'scsherbakov'   => 18543.10,
            'spiridonov'    => 11007.78,
            'kolodyazhniy'  => 2754.63,
            'mikhailov'     => 5522.86,
            'osipov'        => 18493.24,
            'bozhkov'       => 8567.84,
        ];

        $fund_token_count = 0;
        foreach ($balance_matrix as $login => $token_count) {
            $user = User::where('login', $login)->first();
            if (!empty($user)) {
                $user->balance->primary_usd = $this->token_to_usd($token_count);
                $user->balance->body = $token_count;
                $user->balance->bonus = 0;
                $user->balance->save();
                // Accumulate tokens to fund tokens amount
                $fund_token_count += $token_count;
            }
        }

        // Save amount of tokens according to users balance
        $this->fund->token_count = $fund_token_count;
        $this->fund->save();
    }
}
