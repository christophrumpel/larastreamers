<?php

namespace Database\Seeders;

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
        if (! User::count()) {
            User::create([
                'name' => 'Christoph',
                'email' => 'test@test.at',
                'password' => bcrypt('test'),
            ]);
        }

        Stream::truncate();

        Stream::factory()
            ->count(100)
            ->create();

        Stream::factory()
            ->finished()
            ->count(100)
            ->create();
    }
}
