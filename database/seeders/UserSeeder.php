<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            ['name' => 'Admin', 'email' => 'admin@local', 'password' => Hash::make('admin123'), 'role_id' => 1],
            ['name' => 'Test Waiter', 'email' => 'waiter@local', 'password' => Hash::make('waiter123'), 'role_id' => 2],
            ['name' => 'Test Cashier', 'email' => 'cashier@local', 'password' => Hash::make('cashier123'), 'role_id' => 3]
        ]);
    }
}
