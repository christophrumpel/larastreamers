<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        User::create([
            'name' => 'Christoph',
            'email' => 'christoph@christoph-rumpel.com',
            'password' => bcrypt('password'),
        ]);
    }
}
