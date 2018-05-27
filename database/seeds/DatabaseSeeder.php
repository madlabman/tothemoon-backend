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

        $user = User::where('login', 'cherkasov')->first();
        $user->email = 'ser.cherkasov2014@gmail.com';
        $user->phone = '+7 (901) 992-22-22';
        $user->save();
        $user->balance->body = \App\Library\CryptoPrice::convert(2241.88, 'usd', 'btc');
        $user->balance->save();

        $user = User::where('login', 'lopatin')->first();
        $user->email = 'fantazer.1984@mail.ru';
        $user->phone = '+7 (910) 776-46-48';
        $user->save();
        $user->balance->body = \App\Library\CryptoPrice::convert(329, 'usd', 'btc');
        $user->balance->save();

        $user = User::where('login', 'yastrebov')->first();
        $user->email = 'yastrebov_ilya@mail.ru';
        $user->phone = '+7 (999) 806-69-10';
        $user->save();
        $user->balance->body = \App\Library\CryptoPrice::convert(1763, 'usd', 'btc');
        $user->balance->save();

        $user = User::where('login', 'kuznetsov')->first();
        $user->email = 'Katyshenka.l@mail.ru';
        $user->phone = '+7 (967) 002-33-44';
        $user->save();
        $user->balance->body = \App\Library\CryptoPrice::convert(3108, 'usd', 'btc');
        $user->balance->save();

        $user = User::where('login', 'platonov')->first();
        $user->email = 'vladimir_pl91@mail.ru';
//        $user->phone = '+7 (999) 806-69-10';
        $user->save();
        $user->balance->body = \App\Library\CryptoPrice::convert(2600, 'usd', 'btc');
        $user->balance->save();

        $user = User::where('login', 'potapova')->first();
        $user->email = 'potapova_a_v@bk.ru';
        $user->phone = '+7 (919) 009-27-97';
        $user->save();
        $user->balance->body = \App\Library\CryptoPrice::convert(2054, 'usd', 'btc');
        $user->balance->save();

        $user = User::where('login', 'malyshev')->first();
        $user->email = 'moiseicheva2014@mail.ru';
        $user->phone = '+7 (920) 948-28-59';
        $user->save();
        $user->balance->body = \App\Library\CryptoPrice::convert(765.71, 'usd', 'btc');
        $user->balance->save();

        $user = User::where('login', 'berezin')->first();
        $user->email = '89209445050BAN@gmail.com';
        $user->phone = '+7 (920) 944-50-50';
        $user->save();
        $user->balance->body = \App\Library\CryptoPrice::convert(3255, 'usd', 'btc');
        $user->balance->save();

        $user = User::where('login', 'savanin')->first();
        $user->email = 'savanin87@yandex.ru';
        $user->phone = '+7 (915) 791-54-75';
        $user->save();
        $user->balance->body = \App\Library\CryptoPrice::convert(15062.26, 'usd', 'btc');
        $user->balance->save();

        $user = User::where('login', 'tretyak')->first();
        $user->email = 'vtretyak7@gmail.com';
        $user->phone = '+7 (964) 766-79-34';
        $user->save();
        $user->balance->body = \App\Library\CryptoPrice::convert(1478, 'usd', 'btc');
        $user->balance->save();

        $user = User::where('login', 'scsherbakov')->first();
        $user->email = 'infini1y@yandex.ru';
        $user->phone = '+7 (985) 304-80-48';
        $user->save();
        $user->balance->body = \App\Library\CryptoPrice::convert(24161.54, 'usd', 'btc');
        $user->balance->save();

        $user = User::where('login', 'spiridonov')->first();
        $user->email = 'tanzelya_06@mail.ru';
        $user->phone = '+7 (905) 617-69-59';
        $user->save();
        $user->balance->body = \App\Library\CryptoPrice::convert(23508, 'usd', 'btc');
        $user->balance->save();

        $user = User::where('login', 'kolodyazhniy')->first();
        $user->email = 'kolodyazhnaya.73@mail.ru';
        $user->phone = '+7 (926) 363-61-02';
        $user->save();
        $user->balance->body = \App\Library\CryptoPrice::convert(6650, 'usd', 'btc');
        $user->balance->save();

        $user = User::where('login', 'mikhailov')->first();
        $user->email = 'ruslan_mihaylov33@mail.ru';
        $user->phone = '+7 (903) 830-50-88';
        $user->save();
        $user->balance->body = \App\Library\CryptoPrice::convert(15717, 'usd', 'btc');
        $user->balance->save();

        $user = User::where('login', 'osipov')->first();
        $user->email = '79051408000@yandex.ru';
        $user->phone = '+7 (905) 140-80-00';
        $user->save();
        $user->balance->body = \App\Library\CryptoPrice::convert(26188, 'usd', 'btc');
        $user->balance->save();

        $user = User::where('login', 'bozhkov')->first();
        $user->email = 'Evgeniy840804@yandex.ru';
        $user->phone = '+7 (926) 292-06-51';
        $user->save();
        $user->balance->body = \App\Library\CryptoPrice::convert(18933, 'usd', 'btc');
        $user->balance->save();

//        foreach (User::all() as $user) {
//            do {
//                $promo_code = PromoCode::generate(8);
//            } while (User::where('promo_code', $promo_code)->first() !== null);
//            $user->promo_code = $promo_code;
//            $user->save();
//        }
    }
}
