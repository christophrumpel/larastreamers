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
            ->has(Stream::factory()->approved()->finished())
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
            route('archive', ['streamer' => $channel->hashid]),
        ]);
    }

    /** @test */
    public function it_shows_all_streamers(): void
    {
        // Arrange
        Channel::factory()
            ->has(Stream::factory()->approved()->finished())
            ->create(['name' => 'C Channel Dries']);
        Channel::factory()
            ->has(Stream::factory()->approved()->finished())
            ->create(['name' => 'A Channel Mohamed']);
        Channel::factory()
            ->has(Stream::factory()->approved()->finished())
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
    public function it_only_shows_streamers_with_approved_and_finished_streams(): void
    {
        // Arrange
        Stream::factory()
            ->withChannel(['name' => 'Channel Hidden'])
            ->finished()
            ->notApproved()
            ->create();

        Stream::factory()
            ->withChannel(['name' => 'Channel Shown'])
            ->finished()
            ->approved()
            ->create();

        // Act
        $response = $this->get(route('streamers'));

        // Assert
        $response
            ->assertDontSee('Channel Hidden')
            ->assertSee('Channel Shown');
    }

    /** @test */
    public function it_shows_streamers_ordered_by_stream_count(): void
    {
        // Arrange
        Stream::factory()
            ->for(Channel::factory())
            ->approved()
            ->finished()
            ->count(10)
            ->create();
        Stream::factory()
            ->for(Channel::factory())
            ->approved()
            ->finished()
            ->count(1)
            ->create();
        Stream::factory()
            ->for(Channel::factory())
            ->approved()
            ->finished()
            ->count(30)
            ->create();

        // Act
        $response = $this->get(route('streamers'));

        // Assert
        $response->assertSeeText([
            'Show 30 streams',
            'Show 10 streams',
            'Show 1 stream',
        ]);
    }

    /** @test */
    public function it_only_counts_approved_and_finished_streams(): void
    {
        // Arrange
        $channel = Channel::factory()->create();
        Stream::factory()
            ->approved()
            ->finished()
            ->for($channel)
            ->count(10)
            ->create();

        Stream::factory()
            ->notApproved()
            ->for($channel)
            ->count(10)
            ->create();

        Stream::factory()
            ->upcoming()
            ->for($channel)
            ->count(10)
            ->create();

        // Act
        $response = $this->get(route('streamers'));

        // Assert
        $response->assertSeeInOrder([
            'Show 10',
        ]);
    }
}
