<?php

namespace Database\Seeders;

use App\Models\Stream;
use App\Models\User;
use Carbon\Carbon;
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
            ->approved()
            ->upcoming()
            ->create(['scheduled_start_time' => Carbon::now()]);

        Stream::factory()
            ->approved()
            ->upcoming()
            ->count(2)
            ->create(['scheduled_start_time' => Carbon::tomorrow()]);

        Stream::factory()
            ->approved()
            ->upcoming()
            ->count(10)
            ->create();

        Stream::factory()
            ->finished()
            ->count(100)
            ->create();
    }
}
