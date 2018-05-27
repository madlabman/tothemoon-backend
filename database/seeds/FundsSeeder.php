<?php

use App\Fund;
use Illuminate\Database\Seeder;

class FundsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Fund::create([
            'name' => 'ToTheMoon',
            'slug' => 'tothemoon',
        ]);
    }
}
