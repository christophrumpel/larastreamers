<?php

use App\Http\Livewire\StreamListArchive;
use App\Models\Channel;
use App\Models\Stream;
use Livewire\Livewire;
use Tests\TestCase;
use Vinkla\Hashids\Facades\Hashids;


it('only shows streams by selected streamer', function () {
    // Arrange
    Stream::factory()
        ->for(Channel::factory()->create(['name' => 'My Channel']))
        ->finished()
        ->create(['title' => 'Stream Seen', 'channel_id' => 1]);
    Stream::factory()
        ->for(Channel::factory()->create(['name' => 'My Channel']))
        ->finished()
        ->create(['title' => 'Stream Not Seen', 'channel_id' => 2]);

    // Act & Assert
    Livewire::test(StreamListArchive::class)
        ->set('streamer', Hashids::encode(1))
        ->assertSee('Stream Seen')
        ->assertDontSee('Stream Not Seen');
});

it('shows streamers as dropdown options', function () {
    // Arrange
    Channel::factory()
        ->create(['name' => 'Channel A']);
    Channel::factory()
        ->create(['name' => 'Channel B']);

    // Arrange & Act & Assert
    Livewire::test(StreamListArchive::class)
        ->assertSee([
            'Channel A',
            'Channel B',
        ]);
});

it('wires properties and methods', function () {
    // Arrange & Act & Assert
    Livewire::test(StreamListArchive::class)
        ->assertPropertyWired('streamer');
});
