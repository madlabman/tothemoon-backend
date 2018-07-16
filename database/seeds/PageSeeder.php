<?php

use App\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Page::create([
            'title' => 'Команда',
            'content' => 'Пример страницы',
            'slug' => 'command'
        ]);
    }
}
