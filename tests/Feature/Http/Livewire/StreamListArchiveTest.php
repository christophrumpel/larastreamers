<?php

use App\Http\Livewire\StreamListArchive;
use App\Models\Channel;
use App\Models\Stream;
use Livewire\Livewire;
use Vinkla\Hashids\Facades\Hashids;
use App\Services\YouTube\StreamData;

it('only shows streams by selected streamer', function() {
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

it('shows streamers as dropdown options', function() {
    // Arrange
    Stream::factory()
        ->for(Channel::factory()->create(['name' => 'Channel A']))
        ->create(['status' => StreamData::STATUS_FINISHED]);

    Stream::factory()
        ->for(Channel::factory()->create(['name' => 'Channel B']))
        ->create(['status' => StreamData::STATUS_FINISHED]);

    // Arrange & Act & Assert
    Livewire::test(StreamListArchive::class)
        ->assertSee([
            'Channel A',
            'Channel B',
        ]);
});

it('wires properties and methods', function() {
    // Arrange & Act & Assert
    Livewire::test(StreamListArchive::class)
        ->assertPropertyWired('streamer');
});

it('does not show streamer as dropdown option without approved finished streams', function () {
    // Arrange
    Channel::factory()
        ->create(['name' => 'Channel A']);

    // Arrange & Act & Assert
    Livewire::test(StreamListArchive::class)
        ->assertDontSee('Channel A');
});
