<?php

use App\LevelCondition;
use Illuminate\Database\Seeder;

class LevelConditionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Variant 1 to 3 months

        // 1
        LevelCondition::create([
            'title'             => 'Вариант 1',
            'min_duration'      => 1,
            'max_duration'      => 3,
            'min_usd_amount'    => 100,
            'max_usd_amount'    => 1000,
            'investor_pie'      => 0.3,
        ]);
        // 2
        LevelCondition::create([
            'title'             => 'Вариант 2',
            'min_duration'      => 1,
            'max_duration'      => 3,
            'min_usd_amount'    => 1000,
            'max_usd_amount'    => 2500,
            'investor_pie'      => 0.4,
        ]);
        // 3
        LevelCondition::create([
            'title'             => 'Вариант 3',
            'min_duration'      => 1,
            'max_duration'      => 3,
            'min_usd_amount'    => 2500,
            'max_usd_amount'    => 5000,
            'investor_pie'      => 0.45,
        ]);

        // Variant 1 to 6 months

        // 4
        LevelCondition::create([
            'title'             => 'Вариант 4',
            'min_duration'      => 1,
            'max_duration'      => 6,
            'min_usd_amount'    => 5000,
            'max_usd_amount'    => 10000,
            'investor_pie'      => 0.5,
        ]);
        // 5
        LevelCondition::create([
            'title'             => 'Вариант 5',
            'min_duration'      => 1,
            'max_duration'      => 6,
            'min_usd_amount'    => 10000,
            'max_usd_amount'    => 50000,
            'investor_pie'      => 0.55,
        ]);
        // 6
        LevelCondition::create([
            'title'             => 'Вариант 6',
            'min_duration'      => 1,
            'max_duration'      => 6,
            'min_usd_amount'    => 50000,
            'max_usd_amount'    => 100000,
            'investor_pie'      => 0.6,
        ]);
        // 7
        LevelCondition::create([
            'title'             => 'Вариант 7',
            'min_duration'      => 1,
            'max_duration'      => 6,
            'min_usd_amount'    => 100000,
            'max_usd_amount'    => 250000,
            'investor_pie'      => 0.65,
        ]);

        // Variant 1 to 12 months

        // 8
        LevelCondition::create([
            'title'             => 'Вариант 8',
            'min_duration'      => 1,
            'max_duration'      => 12,
            'min_usd_amount'    => 250000,
            'max_usd_amount'    => 500000,
            'investor_pie'      => 0.7,
        ]);
        // 9
        LevelCondition::create([
            'title'             => 'Вариант 9',
            'min_duration'      => 1,
            'max_duration'      => 12,
            'min_usd_amount'    => 500000,
            'max_usd_amount'    => 750000,
            'investor_pie'      => 0.75,
        ]);
        // 10
        LevelCondition::create([
            'title'             => 'Вариант 10',
            'min_duration'      => 1,
            'max_duration'      => 12,
            'min_usd_amount'    => 750000,
            'max_usd_amount'    => 1000000,
            'investor_pie'      => 0.8,
        ]);
        // 11
        LevelCondition::create([
            'title'             => 'Вариант 11',
            'min_duration'      => 1,
            'max_duration'      => 12,
            'min_usd_amount'    => 1000000,
            'max_usd_amount'    => 9999999999,  // imagine that it's unreachable amount
            'investor_pie'      => 0.85,
        ]);
    }
}
