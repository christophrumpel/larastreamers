<?php

use App\Models\Channel;
use App\Models\Stream;
use Carbon\Carbon;
use Vinkla\Hashids\Facades\Hashids;
use function Pest\Laravel\get;

it('shows only finished streams', function () {
    // Arrange
    Stream::factory()
        ->withChannel()
        ->finished()
        ->withTitle('Finished stream')
        ->create();

    Stream::factory()
        ->withChannel()
        ->live()
        ->withTitle('Live stream')
        ->create();

    Stream::factory()
        ->withChannel()
        ->upcoming()
        ->withTitle('Upcoming stream')
        ->create();

    // Act & Assert
    get(route('archive'))
        ->assertDontSee('Live stream')
        ->assertDontSee('Upcoming stream')
        ->assertSee('Finished stream');
});

it('uses actual start time first for order if given', function () {
    // Arrange
    Stream::factory()
        ->withChannel()
        ->finished()
        ->withScheduledStartTime(Carbon::now()->subDays(2))
        ->withActualStartTime(Carbon::now()->subDays(1))
        ->withTitle('Finished latest')
        ->create();

    Stream::factory()
        ->withChannel()
        ->finished()
        ->withScheduledStartTime(Carbon::now()->subDays(1))
        ->withActualStartTime(Carbon::now()->subDays(2))
        ->withTitle('Finished oldest')
        ->create();

    // Act & Assert
    get(route('archive'))
        ->assertSeeInOrder([
            'Finished latest',
            'Finished oldest',
        ]);
});

it('orders streams from latest to oldest', function () {
    // Arrange
    Stream::factory()
        ->withChannel()
        ->finished()
        ->withTitle('Finished one day ago')
        ->withScheduledStartTime(Carbon::yesterday())
        ->create();

    Stream::factory()
        ->withChannel()
        ->finished()
        ->withTitle('Finished two days ago')
        ->withScheduledStartTime(Carbon::yesterday()->subDay())
        ->create();

    Stream::factory()
        ->withChannel()
        ->finished()
        ->withTitle('Finished three days ago')
        ->withScheduledStartTime(Carbon::yesterday()->subDays(2))
        ->create();

    // Act & Assert
    get(route('archive'))
        ->assertSeeInOrder([
            'Finished one day ago',
            'Finished two days ago',
            'Finished three days ago',
        ]);
});

it('does not show deleted streams', function () {
    // Arrange
    Stream::factory()
        ->withChannel()
        ->deleted()
        ->withTitle('Stream deleted')
        ->create();

    Stream::factory()
        ->withChannel()
        ->finished()
        ->withTitle('Stream finished')
        ->create();

    // Act & Assert
    $this
        ->get(route('archive'))
        ->assertSee('Stream finished')
        ->assertDontSee('Stream deleted');
});

it('shows duration of stream if given', function () {
    $this->withoutExceptionHandling();
    // Arrange
    Stream::factory()
        ->withChannel()
        ->finished()
        ->withActualStartTime(Carbon::yesterday())
        ->withActualEndTime(Carbon::yesterday()->addHour()->addMinutes(12))
        ->create();

    // Act & Assert
    get(route('archive'))
        ->assertSee('1h 12m');
});

it('searches for streams on title', function () {
    // Arrange
    Stream::factory()
        ->withChannel()
        ->finished()
        ->withTitle('Stream One')
        ->create();

    Stream::factory()
        ->withChannel()
        ->finished()
        ->withTitle('Stream Two')
        ->create();

    Stream::factory()
        ->withChannel()
        ->finished()
        ->withTitle('Stream Three')
        ->create();

    // Act & Assert
    get(route('archive', ['search' => 'three']))
        ->assertOk()
        ->assertSee([
            'Stream Three',
        ])->assertDontSee([
            'Stream One',
            'Stream Two',
        ]);
});

it('searches for streams on channel title', function () {
    // Arrange
    $channelShown = Channel::factory()->create(['name' => 'Channel Shown']);
    Stream::factory()
        ->finished()
        ->for($channelShown)
        ->withTitle('Stream Shown #1')
        ->create();

    Stream::factory()
        ->finished()
        ->for($channelShown)
        ->withTitle('Stream Shown #2')
        ->create();

    Stream::factory()
        ->finished()
        ->for(Channel::factory()->create(['name' => 'Channel Hidden']))
        ->withTitle('Stream Hidden')
        ->create();

    // Act & Assert
    get(route('archive', ['search' => 'Channel Shown']))
        ->assertOk()
        ->assertSee([
            'Stream Shown #1',
            'Stream Shown #2',
            'Channel Shown',
        ])
        ->assertDontSee([
            'Stream Hidden',
        ]);
});

it('searches
 for streams by specific
 ->$this->withTitle()
  streamer', function () {
    // Arrange
    Stream::factory()
        ->withChannel()
        ->finished()
        ->withTitle('Stream Shown')
        ->create();

    Stream::factory()
        ->withChannel()
        ->finished()
        ->withTitle('Stream Hidden')
        ->create();

    // Act & Assert
    get(route('archive', ['streamer' => Hashids::encode(1)]))
        ->assertOk()
        ->assertSee('Stream Shown')
        ->assertDontSee('Stream Hidden');
});

it('searches for streams by specific streamer and search term', function () {
    // Arrange
    $channel = Channel::factory()->create();
    Stream::factory()
        ->for($channel)
        ->finished()
        ->withTitle('Stream Shown')
        ->create();

    Stream::factory()
        ->for($channel)
        ->finished()
        ->withTitle('Stream Hidden')
        ->create();

    // Act & Assert
    get(route('archive', ['streamer' => '1', 'search' => 'Shown']))
        ->assertOk()
        ->assertSee('Stream Shown')
        ->assertDontSee('Stream Hidden');
});

it('orders streamers by name', function () {
    $this->withoutExceptionHandling();
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
    get(route('archive'))
        ->assertOk()
        ->assertSeeInOrder([
            'Adrian Nürnberger',
            'Caleb Porzio',
            'Christoph Rumpel',
            'Laravel',
        ]);
});
