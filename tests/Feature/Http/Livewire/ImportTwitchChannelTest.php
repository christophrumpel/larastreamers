<?php


use App\Http\Livewire\ImportTwitchChannel;
use App\Models\Channel;
use Tests\Fakes\TwitchResponses;

uses(TwitchResponses::class);

it('adds twitch channel', function () {
    // Arrange
    Http::fake([
        'api.twitch.tv/helix/users*' => Http::response($this->channelResponse()),
        'api.twitch.tv/helix/eventsub/subscriptions*' => Http::response($this->subscriptionOnlineResponse())
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
        'url' => 'https://www.twitch.tv/christophrumpel',
        'thumbnail_url' => "https://profile.url",
        'auto_import' => true,
    ]);
});

it('asks for subscription for channel', function() {
    // Arrange
    Http::fake([
        'api.twitch.tv/helix/users*' => Http::response($this->channelResponse()),
        'api.twitch.tv/helix/eventsub/subscriptions*' => Http::response($this->subscriptionOnlineResponse()),
    ]);

    // Act
    Livewire::test(ImportTwitchChannel::class)
        ->set('channelName', 'christophrumpel')
        ->call('importChannel');

    Http::assertSent(function ($request) {
        return $request->url() == 'https://api.twitch.tv/helix/eventsub/subscriptions'
            && $request['type'] == 'stream.online'
            && $request['version'] == 1
            && $request['condition'] == ['broadcaster_user_id' => '1234']
            && $request['transport'] == ["method" => "webhook", "callback" => route('webhooks'), "secret" => "1234567890"];
    });

    Http::assertSent(function ($request) {
        return $request->url() == 'https://api.twitch.tv/helix/eventsub/subscriptions'
            && $request['type'] == 'stream.offline'
            && $request['version'] == 1
            && $request['condition'] == ['broadcaster_user_id' => '1234']
            && $request['transport'] == ["method" => "webhook", "callback" => route('webhooks'), "secret" => "1234567890"];
    });
});

it('wires channel name', function () {
    // Act & Assert
    Livewire::test(ImportTwitchChannel::class)
        ->assertPropertyWired('channelName');
});

//it('stores channel stream segments', function () {
//    // Arrange
//    Http::fake([
//        'api.twitch.tv/helix/users*' => Http::response($this->channelResponse()),
//        'api.twitch.tv/helix/schedule*' => Http::response($this->scheduleResponse())
//    ]);
//
//    // Assert
//    $this->assertDatabaseCount(Stream::class, 0);
//
//    // Act
//    Livewire::test(ImportTwitchChannel::class)
//        ->set('channelName', 'alexandersix_')
//        ->call('importChannel');
//
//    $this->assertDatabaseCount(Stream::class, 2);
//});
