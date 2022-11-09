<?php


use App\Http\Livewire\ImportTwitchChannel;
use App\Models\Channel;
use Tests\Fakes\TwitchResponses;

uses(TwitchResponses::class);

it('adds twitch channel', function () {
    // Arrange
    Http::fake(fn() => Http::response($this->channelResponse()));

    // Assert
    $this->assertDatabaseCount(Channel::class, 0);

    // Act
    Livewire::test(ImportTwitchChannel::class)
        ->set('channelName', 'christophrumpel')
        ->call('importChannel');

    // Assert
    $this->assertDatabaseCount(Channel::class, 1);
    $this->assertDatabaseHas(Channel::class, [
        'platform' => 'twitch',
        'platform_id' => 'christophrumpel',
        'name' => 'Christoph Rumpel',
        'description' => "my description",
        'thumbnail_url' => "https://profile.url",
    ]);
});

it('wires channel name', function() {
	// Act & Assert
    Livewire::test(ImportTwitchChannel::class)
        ->assertPropertyWired('channelName');
});
