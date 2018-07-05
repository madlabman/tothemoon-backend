<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seed_data = [
            [
                'login' => 'cherkasov',
                'password' => '49ePK6ycT',
                'phone' => '+7 (901) 992-22-22',
                'email' => 'ser.cherkasov2014@gmail.com',
            ],
            [
                'login' => 'lopatin',
                'password' => 'lopatin',
                'phone' => '+7 (910) 776-46-48',
                'email' => 'fantazer.1984@mail.ru',
            ],
            [
                'login' => 'yastrebov',
                'password' => 'qU2pA6G8t',
                'phone' => '+7 (999) 806-69-10',
                'email' => 'yastrebov_ilya@mail.ru',
            ],
            [
                'login' => 'kuznetsov',
                'password' => 'zAu6R7Dy9',
                'phone' => '+7 (967) 002-33-44',
                'email' => 'Katyshenka.l@mail.ru',
            ],
            [
                'login' => 'platonov',
                'password' => '4Di1aa8VF',
                'phone' => null,
                'email' => 'vladimir_pl91@mail.ru',
            ],
            [
                'login' => 'potapova',
                'password' => 'H25xrY2rJ',
                'phone' => '+7 (919) 009-27-97',
                'email' => 'potapova_a_v@bk.ru',
            ],
            [
                'login' => 'malyshev',
                'password' => '8Wwe4e7PZ',
                'phone' => '+7 (920) 948-28-59',
                'email' => 'moiseicheva2014@mail.ru',
            ],
            [
                'login' => 'berezin',
                'password' => 'wpx5X8XJ4',
                'phone' => '+7 (920) 944-50-50',
                'email' => '89209445050BAN@gmail.com',
            ],
            [
                'login' => 'savanin',
                'password' => 'M2x5aMR9f',
                'phone' => '+7 (915) 791-54-75',
                'email' => 'savanin87@yandex.ru',
            ],
            [
                'login' => 'tretyak',
                'password' => '6GUv28raR',
                'phone' => '+7 (964) 766-79-34',
                'email' => 'vtretyak7@gmail.com',
            ],
            [
                'login' => 'scsherbakov',
                'password' => 'E6p6Hvi9S',
                'phone' => '+7 (985) 304-80-48',
                'email' => 'infini1y@yandex.ru',
            ],
            [
                'login' => 'spiridonov',
                'password' => 'pvYnS7Z89',
                'phone' => '+7 (905) 617-69-59',
                'email' => 'tanzelya_06@mail.ru',
            ],
            [
                'login' => 'kolodyazhniy',
                'password' => 'TMkn5iG93',
                'phone' => '+7 (926) 363-61-02',
                'email' => 'kolodyazhnaya.73@mail.ru',
            ],
            [
                'login' => 'mikhailov',
                'password' => 'J7S8iL8fz',
                'phone' => '+7 (903) 830-50-88',
                'email' => 'ruslan_mihaylov33@mail.ru',
            ],
            [
                'login' => 'osipov',
                'password' => 'XSn88gBp8',
                'phone' => '+7 (905) 140-80-00',
                'email' => '79051408000@yandex.ru',
            ],
            [
                'login' => 'bozhkov',
                'password' => 'twYT75mG4',
                'phone' => '+7 (926) 292-06-51',
                'email' => 'Evgeniy840804@yandex.ru',
            ],
        ];

        foreach ($seed_data as $user) {
            $user['password'] = bcrypt($user['password']);
            $db_user = User::updateOrCreate($user);
            $balance = new \App\Balance();
            $db_user->balance()->save($balance);
        }

//        foreach (User::all() as $user) {
//            do {
//                $promo_code = PromoCode::generate(8);
//            } while (User::where('promo_code', $promo_code)->first() !== null);
//            $user->promo_code = $promo_code;
//            $user->save();
//        }
    }
}
