<?php

use App\Facades\Twitch;
use Tests\Fakes\TwitchResponses;

uses(TwitchResponses::class);

it('can fetch channel details', function () {
   // Arrange
    Http::fake([
        'api.twitch.tv/helix/channels*' => Http::response($this->twitchChannelResponse()),
    ]);

    // Act
    $channel = Twitch::channel('1234');

    // Assert
    expect($channel)
        ->broadcasterId->toBe('1234')
        ->broadcasterLogin->toBe('christophrumpel')
        ->broadcasterName->toBe('christophrumpel')
        ->gameId->toBe('509670')
        ->gameName->toBe('Science & Technology')
        ->title->toBe('📺 Redesigning Larastreamers ')
        ->dekay->toBeNull();
});
