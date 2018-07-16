<?php

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
        $this->call(FundsSeeder::class);
        $this->call(LevelConditionsSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(TokensSeeder::class);
        $this->call(PageSeeder::class);
    }
}
