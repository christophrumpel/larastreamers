<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\Stream;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Christoph',
            'email' => 'test@test.at',
            'password' => bcrypt('test'),
        ]);

        Channel::factory()
            ->count(10)
            ->create();

        Stream::factory()
            ->count(10)
            ->create();
    }
}
