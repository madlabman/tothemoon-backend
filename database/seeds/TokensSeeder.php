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
        if (!empty($this->fund)) return;

        $user = User::where('login', 'cherkasov')->first();
        $user->balance->token = 1391.81;
        $user->balance->body = CryptoPrice::convert($this->token_to_usd($user->balance->token), 'usd', 'btc');
        $user->balance->save();

        $user = User::where('login', 'lopatin')->first();
        $user->balance->token = 204.39;
        $user->balance->body = CryptoPrice::convert($this->token_to_usd($user->balance->token), 'usd', 'btc');
        $user->balance->save();

        $user = User::where('login', 'yastrebov')->first();
        $user->balance->token = 1134.20;
        $user->balance->body = CryptoPrice::convert($this->token_to_usd($user->balance->token), 'usd', 'btc');
        $user->balance->save();

        $user = User::where('login', 'kuznetsov')->first();
        $user->balance->token = 1796.99;
        $user->balance->body = CryptoPrice::convert($this->token_to_usd($user->balance->token), 'usd', 'btc');
        $user->balance->save();

        $user = User::where('login', 'platonov')->first();
        $user->balance->token = 1440.84;
        $user->balance->body = CryptoPrice::convert($this->token_to_usd($user->balance->token), 'usd', 'btc');
        $user->balance->save();

        $user = User::where('login', 'potapova')->first();
        $user->balance->token = 1094.90;
        $user->balance->body = CryptoPrice::convert($this->token_to_usd($user->balance->token), 'usd', 'btc');
        $user->balance->save();

        $user = User::where('login', 'malyshev')->first();
        $user->balance->token = 361.24;
        $user->balance->body = CryptoPrice::convert($this->token_to_usd($user->balance->token), 'usd', 'btc');
        $user->balance->save();

        $user = User::where('login', 'berezin')->first();
        $user->balance->token = 1801.59;
        $user->balance->body = CryptoPrice::convert($this->token_to_usd($user->balance->token), 'usd', 'btc');
        $user->balance->save();

        $user = User::where('login', 'savanin')->first();
        $user->balance->token = 7805.95;
        $user->balance->body = CryptoPrice::convert($this->token_to_usd($user->balance->token), 'usd', 'btc');
        $user->balance->save();

        $user = User::where('login', 'tretyak')->first();
        $user->balance->token = 697.84;
        $user->balance->body = CryptoPrice::convert($this->token_to_usd($user->balance->token), 'usd', 'btc');
        $user->balance->save();

        $user = User::where('login', 'scsherbakov')->first();
        $user->balance->token = 18543.10;
        $user->balance->body = CryptoPrice::convert($this->token_to_usd($user->balance->token), 'usd', 'btc');
        $user->balance->save();

        $user = User::where('login', 'spiridonov')->first();
        $user->balance->token = 11007.78;
        $user->balance->body = CryptoPrice::convert($this->token_to_usd($user->balance->token), 'usd', 'btc');
        $user->balance->save();

        $user = User::where('login', 'kolodyazhniy')->first();
        $user->balance->token = 4694.31;
        $user->balance->body = CryptoPrice::convert($this->token_to_usd($user->balance->token), 'usd', 'btc');
        $user->balance->save();

        $user = User::where('login', 'mikhailov')->first();
        $user->balance->token = 7077.10;
        $user->balance->body = CryptoPrice::convert($this->token_to_usd($user->balance->token), 'usd', 'btc');
        $user->balance->save();

        $user = User::where('login', 'osipov')->first();
        $user->balance->token = 18493.24;
        $user->balance->body = CryptoPrice::convert($this->token_to_usd($user->balance->token), 'usd', 'btc');
        $user->balance->save();

        $user = User::where('login', 'bozhkov')->first();
        $user->balance->token = 8765.56;
        $user->balance->body = CryptoPrice::convert($this->token_to_usd($user->balance->token), 'usd', 'btc');
        $user->balance->save();
    }
}
