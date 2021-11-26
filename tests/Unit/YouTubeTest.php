<?php

use App\Facades\YouTube;
use App\Services\YouTube\StreamData;
use Illuminate\Support\Facades\Http;
use Tests\Fakes\YouTubeResponses;
use Tests\TestCase;


it('can fetch channel details from youtube', function () {
    // Arrange
    Http::fake(fn() => Http::response($this->channelResponse()));

    // Act
    $channel = YouTube::channel('UCdtd5QYBx9MUVXHm7qgEpxA');

    // Assert
    expect($channel)
        ->youTubeCustomUrl->toBe('christophrumpel')
        ->name->toBe('Christoph Rumpel')
        ->description->toStartWith('Hi, I\'m Christoph Rumpel')
        ->onPlatformSince->toIso8601String()->toBe('2010-01-12T19:15:29+00:00')
        ->country->toBe('AT')
        ->thumbnailUrl->toBe('https://yt3.ggpht.com/ytc/AAUvwniFZUkXnS4vDKY4lDohrpFsyu1V2AJwt4CFZGy25Q=s800-c-k-c0x00ffffff-no-rj');
});

it('can fetch upcoming streams from youtube', function () {
    // Arrange
    Http::fake([
        '*search*' => Http::response($this->upcomingStreamsResponse()),
        '*video*' => Http::response($this->videoResponse()),
    ]);

    // Act
    $streams = YouTube::upcomingStreams('UCNlUCA4VORBx8X-h-rXvXEg');

    // Assert
    expect($streams)->toHaveCount(3);

    /** @var \App\Services\YouTube\StreamData $finishedStream */
    $finishedStream = $streams->first();

    expect($finishedStream->videoId)->toEqual('gzqJZQyfkaI');
    expect($finishedStream->title)->toEqual('Live coding new features for larastreamers.com');
    expect($finishedStream->description)->toEqual("Christoph Rumpel created a nice new project: https://larastreamers.com/\nIn this stream, I'm going to add some features to Christoph's app.");
    expect($finishedStream->thumbnailUrl)->toEqual('https://i.ytimg.com/vi/gzqJZQyfkaI/maxresdefault.jpg');
    expect($finishedStream->publishedAt->toIso8601String())->toEqual('2021-05-15T12:51:18+00:00');
    expect($finishedStream->plannedStart->toIso8601String())->toEqual('2031-05-15T11:00:00+00:00');
    expect($finishedStream->actualStartTime->toIso8601String())->toEqual('2031-05-15T11:00:29+00:00');
    expect($finishedStream->actualEndTime->toIso8601String())->toEqual('2031-05-15T11:30:29+00:00');
    expect($finishedStream->status)->toEqual(StreamData::STATUS_FINISHED);

    /** @var \App\Services\YouTube\StreamData $upcomingStream */
    $upcomingStream = $streams->last();

    expect($upcomingStream->videoId)->toEqual('L3O1BbybSgw');
    expect($upcomingStream->title)->toEqual('Casual Artisan Call #7');
    expect($upcomingStream->thumbnailUrl)->toEqual('https://i.ytimg.com/vi/L3O1BbybSgw/maxresdefault_live.jpg');
    expect($upcomingStream->publishedAt->toIso8601String())->toEqual('2021-05-14T17:00:28+00:00');
    expect($upcomingStream->plannedStart->toIso8601String())->toEqual('2021-05-21T09:00:00+00:00');
    expect($upcomingStream->actualStartTime)->toBeNull();
    expect($upcomingStream->actualEndTime)->toBeNull();
    expect($upcomingStream->status)->toEqual(StreamData::STATUS_UPCOMING);
});

it('uses real api key when needed', function () {
    expect(config()->get('services.youtube.key'))->toEqual('FAKE-YOUTUBE-KEY');

    $this->useRealYoutubeApi();

    config()->set('services.youtube.key', 'REAL-API-KEY');
    expect(config()->get('services.youtube.key'))->toEqual('REAL-API-KEY');
});
