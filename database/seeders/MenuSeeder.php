<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menus')->insert([
            ['name' => 'Burger', 'price' => 20000, 'is_available' => true, 'type_menu_id' => 1],
            ['name' => 'Hotdog', 'price' => 15000, 'is_available' => false, 'type_menu_id' => 1],
            ['name' => 'Coca cola', 'price' => 10000, 'is_available' => true, 'type_menu_id' => 2]
        ]);
    }
}
