<?php

use App\Balance;
use App\Library\PromoCode;
use App\Message;
use App\Signal;
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
//        $user = User::create([
//            'name'  => 'nwdawdw_admin',
//            'email' => 'test1@test.ru',
//        ]);
//        $user->password = bcrypt('testtest');
//        $user->save();
//
//        echo $user->id;

//        /** @var User $user */
//        $user = User::find(65);
//
//        $balance = $user->balance;
//        $balance->delete();
//
//        $balance = new Balance();
//        $user->balance()->save($balance);
//
//        $user = $balance->user;
//        echo $user->id . PHP_EOL;

//        $signal = Signal::create([
//            'info' => 'wdawd',
//            'level' => Signal::GREEN_LEVEL,
//        ]);

//        $admin = User::where('login', 'admin')->first();
//        $test = User::where('login', 'test')->first();
//
//        $message = Message::create([
//            'text' => 'Привет, тест!',
//            'sender' => $admin->uuid,
//        ]);
//        $message->toUser()->save($test);
//        $relation = $message->fromUser()->associate($admin);
//        $relation->save();
//
//        $message = Message::create([
//            'text' => 'Привет, админ!',
//            'sender' => $test->uuid,
//        ]);
//        $message->toUser()->save($admin);
//        $relation = $message->fromUser()->associate($test);
//        $relation->save();
//
//        \App\Fund::create([
//            'name' => 'ToTheMoon',
//            'slug' => 'tothemoon',
//        ]);

//        $user = User::where('login', 'cherkasov')->first();
//        $user->balance->body = \App\Library\CryptoPrice::convert(1831, 'usd', 'btc');
//        $user->balance->save();
//
//        $user = User::where('login', 'lopatin')->first();
//        $user->balance->body = \App\Library\CryptoPrice::convert(269, 'usd', 'btc');
//        $user->balance->save();
//
//        $user = User::where('login', 'yastrebov')->first();
//        $user->balance->body = \App\Library\CryptoPrice::convert(1492, 'usd', 'btc');
//        $user->balance->save();
//
//        $user = User::where('login', 'kuznetsov')->first();
//        $user->balance->body = \App\Library\CryptoPrice::convert(2544, 'usd', 'btc');
//        $user->balance->save();
//
//        $user = User::where('login', 'platonov')->first();
//        $user->balance->body = \App\Library\CryptoPrice::convert(1896, 'usd', 'btc');
//        $user->balance->save();
//
//        $user = User::where('login', 'potapova')->first();
//        $user->balance->body = \App\Library\CryptoPrice::convert(1665, 'usd', 'btc');
//        $user->balance->save();
//
//        $user = User::where('login', 'malyshev')->first();
//        $user->balance->body = \App\Library\CryptoPrice::convert(475, 'usd', 'btc');
//        $user->balance->save();
//
//        $user = User::where('login', 'berezin')->first();
//        $user->balance->body = \App\Library\CryptoPrice::convert(2371, 'usd', 'btc');
//        $user->balance->save();
//
//        $user = User::where('login', 'savanin')->first();
//        $user->balance->body = \App\Library\CryptoPrice::convert(10975, 'usd', 'btc');
//        $user->balance->save();
//
//        $user = User::where('login', 'tretyak')->first();
//        $user->balance->body = \App\Library\CryptoPrice::convert(918, 'usd', 'btc');
//        $user->balance->save();

//        foreach (User::all() as $user) {
//            do {
//                $promo_code = PromoCode::generate(8);
//            } while (User::where('promo_code', $promo_code)->first() !== null);
//            $user->promo_code = $promo_code;
//            $user->save();
//        }
    }
}
