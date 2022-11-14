<?php


use App\Enums\TwitchEventType;
use App\Models\Channel;
use App\Models\Stream;
use App\Models\TwitchChannelSubscription;
use Tests\Fakes\TwitchResponses;

uses(TwitchResponses::class);

it('handles Twitch event subscription verification for stream online event', function () {
    // Arrange
    $channel = Channel::factory()->create();
    $unverifiedSubscription = TwitchChannelSubscription::factory()->create(
        [
            'channel_id' => $channel->id,
            'subscription_event' => TwitchEventType::STREAM_ONLINE
        ]
    );

    // Act
    $this->post(
        route('webhooks'),
        $this->verifyEventSubscriptionPayload(TwitchEventType::STREAM_ONLINE, $channel),
        ['twitch-eventsub-message-type' => 'webhook_callback_verification']
    );

    // Assert
    expect($unverifiedSubscription->refresh())
        ->verified
        ->toBeTrue();
});

it('handles Twitch event subscription verification for stream offline event', function () {
    // Arrange
    $channel = Channel::factory()->create();
    $unverifiedSubscription = TwitchChannelSubscription::factory()->create(
        [
            'channel_id' => $channel->id,
            'subscription_event' => TwitchEventType::STREAM_OFFLINE
        ]
    );

    // Act
    $this->post(
        route('webhooks'),
        $this->verifyEventSubscriptionPayload(TwitchEventType::STREAM_OFFLINE, $channel),
        ['twitch-eventsub-message-type' => 'webhook_callback_verification']
    );

    // Assert
    expect($unverifiedSubscription->refresh())
        ->verified
        ->toBeTrue();
});

it('handles if channel not given for event', function() {
	// Arrange
    Http::fake([
        'api.twitch.tv/helix/channels*' => Http::response($this->twitchChannelResponse()),
    ]);
    $channelNotStored = Channel::factory()->make();

    // Act
    $this->post(
        route('webhooks'),
        $this->streamOnlineEventPayload($channelNotStored),
        ['twitch-eventsub-message-type' => 'notification']
    );

    // Assert
    $this->assertDatabaseCount(Stream::class, 0);
});

it('handles stream online event', function() {
	// Arrange
    Http::fake([
        'api.twitch.tv/helix/channels*' => Http::response($this->twitchChannelResponse()),
    ]);
    $channel = Channel::factory()->create(['platform_id' => '1234']);

	// Act
    $this->post(
        route('webhooks'),
        $this->streamOnlineEventPayload($channel),
        ['twitch-eventsub-message-type' => 'notification']
    );

    // Assert
    $this->assertDatabaseCount(Stream::class, 1);

});
