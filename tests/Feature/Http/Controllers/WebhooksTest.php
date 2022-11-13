<?php


use App\Enums\TwitchEventSubscription;
use App\Models\Channel;
use App\Models\TwitchChannelSubscription;
use Tests\Fakes\TwitchResponses;

uses(TwitchResponses::class);

it('adds online event subscription to channel', function () {
    // Arrange
    $channel = Channel::factory()->create();

    // Act
    $this->post(route('webhooks'), $this->eventVerificationResponse(TwitchEventSubscription::STREAM_ONLINE, $channel), [
        'twitch-eventsub-message-type' => 'webhook_callback_verification'
    ]);

    // Assert
    $this->assertDatabaseHas(TwitchChannelSubscription::class, [
        'channel_id' => $channel->id,
        'subscription_event' => TwitchEventSubscription::STREAM_ONLINE,
    ]);
});

it('adds offline event subscription to channel', function () {
    // Arrange
    $channel = Channel::factory()->create();

    // Act
    $this->post(route('webhooks'), $this->eventVerificationResponse(TwitchEventSubscription::STREAM_OFFLINE, $channel), [
        'twitch-eventsub-message-type' => 'webhook_callback_verification'
    ]);

    // Assert
    $this->assertDatabaseHas(TwitchChannelSubscription::class, [
        'channel_id' => $channel->id,
        'subscription_event' => TwitchEventSubscription::STREAM_OFFLINE
    ]);
});
