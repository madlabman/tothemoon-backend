<?php

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class DebugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::find(0)->update([
            'uuid' => Uuid::uuid1()
        ]);
//        foreach (\App\User::all() as $user) {
//            $user->calculateLevel();
//        }
        foreach (\App\User::all() as $user) {
            if (empty($user->balance)) {
                $balance = new \App\Balance();
                $user->balance()->save($balance);
            }
        }
    }
}
