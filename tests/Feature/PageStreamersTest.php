<?php

namespace Tests\Feature;

use App\Models\Channel;
use App\Models\Stream;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PageStreamersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_channel_data(): void
    {
        // Arrange
        $channel = Channel::factory()
            ->create(['name' => 'Channel Dries']);

        // Act
        $response = $this->get(route('streamers'));

        // Assert
        $response->assertSee([
            $channel->name,
            $channel->country,
            Str::of($channel->description)->limit(100),
            $channel->thumbnail_url,
            "https://twitter.com/$channel->twitter_handle",
            route('archive', ['search' => $channel->slug]),
        ]);
    }

    public function it_adds_search_link_with_channel_name_if_slug_not_given(): void
    {
        // Arrange
        $channel = Channel::factory()
            ->create(['name' => 'Channel Dries']);

        // Act
        $response = $this->get(route('streamers'));

        // Assert
        $response->assertSee([
            route('archive', ['search' => $channel->name]),
        ]);
    }


    /** @test */
    public function it_shows_all_streamers(): void
    {
        // Arrange
        Channel::factory()
            ->create(['name' => 'C Channel Dries']);
        Channel::factory()
            ->create(['name' => 'A Channel Mohamed']);
        Channel::factory()
            ->create(['name' => 'B Channel Steve']);

        // Act
        $response = $this->get(route('streamers'));

        // Assert
        $response->assertSee([
            'A Channel Mohamed',
            'B Channel Steve',
            'C Channel Dries',
        ]);
    }

    /** @test */
    public function it_shows_streamers_ordered_by_stream_count(): void
    {
        // Arrange
        Stream::factory()
            ->for(Channel::factory())
            ->count(10)
            ->create();
        Stream::factory()
            ->for(Channel::factory())
            ->count(20)
            ->create();
        Stream::factory()
            ->for(Channel::factory())
            ->count(30)
            ->create();

        // Act
        $response = $this->get(route('streamers'));

        // Assert
        $response->assertSeeInOrder([
            'Show 30',
            'Show 20',
            'Show 10',
        ]);
    }
}
