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
        if (! User::count()) {
            User::create([
                'name' => 'Christoph',
                'email' => 'test@test.at',
                'password' => bcrypt('test'),
            ]);
        }

        Channel::truncate();
        Stream::truncate();

        $channels = Channel::factory()->count(4)->create();

        Stream::factory()
            ->approved()
            ->live()
            ->for($channels->random())
            ->count(3)
            ->create();

        Stream::factory()
            ->approved()
            ->upcoming()
            ->for($channels->random())
            ->count(100)
            ->create();

        Stream::factory()
            ->finished()
            ->for($channels->skip(1)->first())
            ->count(50)
            ->create(['channel_title' => $channels->skip(1)->first()->name]);

        Stream::factory()
            ->finished()
            ->for($channels->first())
            ->count(50)
            ->create(['channel_title' => $channels->first()->name]);
    }
}
