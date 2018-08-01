<?php

use Illuminate\Database\Seeder;

class DebugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
