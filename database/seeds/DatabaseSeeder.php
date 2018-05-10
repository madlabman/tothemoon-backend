<?php

use App\Balance;
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

        $admin = User::where('login', 'admin')->first();
        $test = User::where('login', 'test')->first();

        $message = Message::create([
            'text' => 'Привет, тест!',
            'sender' => $admin->uuid,
        ]);
        $message->toUser()->save($test);
        $relation = $message->fromUser()->associate($admin);
        $relation->save();

        $message = Message::create([
            'text' => 'Привет, админ!',
            'sender' => $test->uuid,
        ]);
        $message->toUser()->save($admin);
        $relation = $message->fromUser()->associate($test);
        $relation->save();
    }
}
