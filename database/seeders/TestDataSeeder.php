<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\Stream;
use App\Models\User;
use App\Services\YouTube\StreamData;
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

        $christoph = Channel::factory()->create(['name' => 'Christoph Rumpel']);
        $steve = Channel::factory()->create(['name' => 'JustSteveKing']);
        $beerAndCode = Channel::factory()->create(['name' => 'Beer  & Code']);
        $nuno = Channel::factory()->create(['name' => 'Nuno Maduro']);
        $freek = Channel::factory()->create(['name' => 'Freek Van der Herten']);
        $caleb = Channel::factory()->create(['name' => 'Caleb Porzio']);
        $matt = Channel::factory()->create(['name' => 'Matt Stauffer']);

        Stream::insert([
            [
                'title' => '📺 Larastreamers - Add Streamer Search',
                'channel_id' => $christoph->id,
                'description' => "Let's add a new search, so that you can see streams only by a specific streamer.",
                'thumbnail_url' => 'https://i.ytimg.com/vi/nJooU7nWMI0/maxresdefault.jpg',
                'stream_id' => '123541',
                'scheduled_start_time' => now()->subMinutes(10),
                'status' => StreamData::STATUS_LIVE,
                'approved_at' => now(),
            ],
            [
                'title' => "Let's play with Laravel LiveWire",
                'channel_id' => $steve->id,
                'description' => 'In this stream I will be inviting Tom Witkowski (@devgummibear) to join me as I build some things in LiveWire, asking some questions and seeing what we can build.',
                'thumbnail_url' => 'https://i.ytimg.com/vi/cQT37gxGr6g/maxresdefault.jpg',
                'stream_id' => '123542',
                'scheduled_start_time' => now()->addMinutes(10),
                'status' => StreamData::STATUS_UPCOMING,
                'approved_at' => now(),
            ],
            [
                'title' => 'Livewire Wire UI com Pedro Henrique - Petiscando #072',
                'channel_id' => $beerAndCode->id,
                'description' => 'faaala galera!!
Hoje(10/10) vamos receber o Pedro Henrique para aquele bate papo diferenciado sobre Livewire, então já sabe né??
Prepara aquele petisco de lei e cooola no chat com a gente!!',
                'thumbnail_url' => 'https://i.ytimg.com/vi/_Rg30_tQRPs/maxresdefault.jpg',
                'stream_id' => '123543',
                'scheduled_start_time' => now()->addMinutes(100),
                'status' => StreamData::STATUS_UPCOMING,
                'approved_at' => now(),
            ],
            [
                'title' => "PEST Meetup #1: Testing Livewire with PEST & You know the REST, now it's time for Pest",
                'channel_id' => $nuno->id,
                'description' => 'Pest is an elegant PHP Testing Framework with a focus on simplicity. It was carefully crafted to bring the joy of testing to PHP.',
                'thumbnail_url' => 'https://i.ytimg.com/vi/q_8kRlAIyms/maxresdefault.jpg',
                'stream_id' => '123544',
                'scheduled_start_time' => now()->addMinutes(456),
                'status' => StreamData::STATUS_UPCOMING,
                'approved_at' => now(),
            ],
            [
                'title' => 'Laravel Meetup #11: Building APIs & Spotlight and Modals With Livewire',
                'channel_id' => $freek->id,
                'description' => 'https://meetup.laravel.com',
                'thumbnail_url' => 'https://i.ytimg.com/vi/0pylMAlfw5k/maxresdefault.jpg',
                'stream_id' => '123545',
                'scheduled_start_time' => now()->addMinutes(254),
                'status' => StreamData::STATUS_UPCOMING,
                'approved_at' => now(),
            ],
            [
                'title' => 'Working on Livewire',
                'channel_id' => $caleb->id,
                'description' => "Let's work on some Livewire pull requests. We will start by checking out the current issues and then move one to more interesting tasks.",
                'thumbnail_url' => 'https://i.ytimg.com/vi/KHC3Qyh1oQA/maxresdefault.jpg',
                'stream_id' => '123546',
                'scheduled_start_time' => now()->addMinutes(10),
                'status' => StreamData::STATUS_FINISHED,
                'approved_at' => now(),
            ],
            [
                'title' => 'Events and Nesting in Laravel Livewire, pairing with Caleb Porzio - Matt Stauffer Livestream',
                'channel_id' => $matt->id,
                'description' => "Apologies: The first few minutes of this stream were cut by glitches in YouTube's editor.",
                'thumbnail_url' => 'https://i.ytimg.com/vi/y3TQq534dRM/maxresdefault.jpg',
                'stream_id' => '123547',
                'scheduled_start_time' => now()->subDays(1),
                'status' => StreamData::STATUS_FINISHED,
                'approved_at' => now(),
            ],
            [
                'title' => '📺 Larastreamers - Add A Streamers Overview',
                'channel_id' => $christoph->id,
                'description' => 'What fits a live stream better than working on a platform that promotes live streams? 😅
Today we implement a new streamers-overview page for https://larastreamers.com/. It will show ',
                'thumbnail_url' => 'https://i.ytimg.com/vi/NLnf2VdBdhc/maxresdefault.jpg',
                'stream_id' => '123548',
                'scheduled_start_time' => now()->subDays(2),
                'status' => StreamData::STATUS_FINISHED,
                'approved_at' => now(),
            ],
            [
                'title' => 'Adote um Dev - TALL Stack projeto prático do zero - #002',
                'channel_id' => $beerAndCode->id,
                'description' => 'Faaala artesãos!!!
E hoje(019/06) vamos dar sequencia ao nosso projeto Web do Zero, vamos falar muito e aprender ',
                'thumbnail_url' => 'https://i.ytimg.com/vi/F0unkkovzA8/maxresdefault.jpg',
                'stream_id' => '123549',
                'scheduled_start_time' => now()->subDays(3),
                'status' => StreamData::STATUS_FINISHED,
                'approved_at' => now(),
            ],
        ]);
    }
}
