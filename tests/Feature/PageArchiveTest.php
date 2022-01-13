<?php

use App\Models\Channel;
use App\Models\Stream;
use Carbon\Carbon;
use Vinkla\Hashids\Facades\Hashids;

it('shows only finished streams', function() {
    // Arrange
    Stream::factory()->withChannel()->finished()->create(['title' => 'Finished stream']);
    Stream::factory()->withChannel()->live()->create(['title' => 'Live stream']);
    Stream::factory()->withChannel()->upcoming()->create(['title' => 'Upcoming stream']);

    // Act & Assert
    $this->get(route('archive'))
        ->assertDontSee('Live stream')
        ->assertDontSee('Upcoming stream')
        ->assertSee('Finished stream');
});

it('orders streams from latest to oldest', function() {
    // Arrange
    Stream::factory()->withChannel()->finished()->create(['title' => 'Finished one day ago', 'scheduled_start_time' => Carbon::yesterday()]);
    Stream::factory()->withChannel()->finished()->create(['title' => 'Finished two days ago', 'scheduled_start_time' => Carbon::yesterday()->subDay()]);
    Stream::factory()->withChannel()->finished()->create(['title' => 'Finished three days ago', 'scheduled_start_time' => Carbon::yesterday()->subDays(2)]);

    // Act & Assert
    $this->get(route('archive'))
        ->assertSeeInOrder([
            'Finished one day ago',
            'Finished two days ago',
            'Finished three days ago',
        ]);
});

it('does not show deleted streams', function() {
    // Arrange
    Stream::factory()
        ->withChannel()
        ->deleted()
        ->create(['title' => 'Stream deleted']);

    Stream::factory()
        ->withChannel()
        ->finished()
        ->create(['title' => 'Stream finished']);

    // Act & Assert
    $this
        ->get(route('archive'))
        ->assertSee('Stream finished')
        ->assertDontSee('Stream deleted');
});

it('shows duration of stream if given', function() {
    // Arrange
    Stream::factory()
        ->withChannel()
        ->finished()
        ->create([
            'actual_start_time' => Carbon::yesterday(),
            'actual_end_time' => Carbon::yesterday()->addHour()->addMinutes(12),
        ]);

    // Act & Assert
    $this
        ->get(route('archive'))
        ->assertSee('1h 12m');
});

it('searches for streams on title', function() {
    // Arrange
    Stream::factory()->withChannel()->finished()->create(['title' => 'Stream One']);
    Stream::factory()->withChannel()->finished()->create(['title' => 'Stream Two']);
    Stream::factory()->withChannel()->finished()->create(['title' => 'Stream Three']);

    // Act & Assert
    $this->get(route('archive', ['search' => 'three']))
        ->assertSee([
            'Stream Three',
        ])->assertDontSee([
            'Stream One',
            'Stream Two',
        ]);
});

it('searches for streams on channel title', function() {
    // Arrange
    $channelShown = Channel::factory()->create(['name' => 'Channel Shown']);
    Stream::factory()
        ->finished()
        ->for($channelShown)
        ->create(['title' => 'Stream Shown #1']);

    Stream::factory()
        ->finished()
        ->for($channelShown)
        ->create(['title' => 'Stream Shown #2']);

    Stream::factory()
        ->finished()
        ->for(Channel::factory()->create(['name' => 'Channel Hidden']))
        ->create(['title' => 'Stream Hidden']);

    // Act & Assert
    $this->get(route('archive', ['search' => 'Channel Shown']))
        ->assertSee([
            'Stream Shown #1',
            'Stream Shown #2',
            'Channel Shown',
        ])
        ->assertDontSee([
            'Stream Hidden',
        ]);
});

it('searches for streams by specific streamer', function() {
    // Arrange
    Stream::factory()->withChannel()->finished()->create(['title' => 'Stream Shown']);
    Stream::factory()->withChannel()->finished()->create(['title' => 'Stream Hidden']);

    // Act & Assert
    $this->get(route('archive', ['streamer' => Hashids::encode(1)]))
        ->assertSee('Stream Shown')
        ->assertDontSee('Stream Hidden');
});

it('searches for streams by specific streamer and search term', function() {
    // Arrange
    $channel = Channel::factory()->create();
    Stream::factory()->for($channel)->finished()->create(['title' => 'Stream Shown']);
    Stream::factory()->for($channel)->finished()->create(['title' => 'Stream Hidden']);

    // Act & Assert
    $this->get(route('archive', ['streamer' => '1', 'search' => 'Shown']))
        ->assertSee('Stream Shown')
        ->assertDontSee('Stream Hidden');
});

it('orders streamers by name', function() {
    // Arrange
    Channel::factory()
        ->has(Stream::factory()->finished())
        ->create(['name' => 'Laravel']);
    Channel::factory()
        ->has(Stream::factory()->finished())
        ->create(['name' => 'Christoph Rumpel']);
    Channel::factory()
        ->has(Stream::factory()->finished())
        ->create(['name' => 'Adrian Nürnberger']);
    Channel::factory()
        ->has(Stream::factory()->finished())
        ->create(['name' => 'Caleb Porzio']);

    // Act & Assert
    $this->get(route('archive'))
        ->assertSeeInOrder([
            'Adrian Nürnberger',
            'Caleb Porzio',
            'Christoph Rumpel',
            'Laravel',
        ]);
});
