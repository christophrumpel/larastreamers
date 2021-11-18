<?php

use App\Models\Channel;
use App\Models\Stream;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

uses(RefreshDatabase::class);

it('shows channel data', function () {
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
});

it('shows all streamers', function () {
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
});

it('only shows streamers with approved and finished streams', function () {
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
});

it('shows streamers ordered by stream count', function () {
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
});

it('only counts approved and finished streams', function () {
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
});
