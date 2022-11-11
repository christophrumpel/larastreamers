<?php


use App\Http\Livewire\ImportTwitchChannel;
use App\Models\Channel;
use App\Models\Stream;
use Tests\Fakes\TwitchResponses;

uses(TwitchResponses::class);

it('adds twitch channel', function () {
    // Arrange
    Http::fake([
        'api.twitch.tv/helix/users*' => Http::response($this->channelResponse()),
        'api.twitch.tv/helix/schedule*' => Http::response($this->scheduleResponse())
    ]);

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
        'platform_id' => '1234',
        'name' => 'Christoph Rumpel',
        'description' => "my description",
        'youtube_custom_url' => 'https://www.twitch.tv/christophrumpel',
        'thumbnail_url' => "https://profile.url",
    ]);
});

it('stores channel stream segments', function () {
    // Arrange
    Http::fake([
        'api.twitch.tv/helix/users*' => Http::response($this->channelResponse()),
        'api.twitch.tv/helix/schedule*' => Http::response($this->scheduleResponse())
    ]);

    // Assert
    $this->assertDatabaseCount(Stream::class, 0);

    // Act
    Livewire::test(ImportTwitchChannel::class)
        ->set('channelName', 'alexandersix_')
        ->call('importChannel');

    $this->assertDatabaseCount(Stream::class, 2);
});

it('wires channel name', function () {
    // Act & Assert
    Livewire::test(ImportTwitchChannel::class)
        ->assertPropertyWired('channelName');
});
