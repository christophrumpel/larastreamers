<?php

use App\Facades\YouTube;
use App\Services\YouTube\StreamData;
use Illuminate\Support\Facades\Http;
use Tests\Fakes\YouTubeResponses;
use Tests\TestCase;

uses(TestCase::class);
uses(YouTubeResponses::class);

it('can fetch channel details from youtube', function () {
    // Arrange
    Http::fake(fn() => Http::response($this->channelResponse()));

    // Act
    $channel = YouTube::channel('UCdtd5QYBx9MUVXHm7qgEpxA');

    // Assert
    $this->assertEquals('christophrumpel', $channel->youTubeCustomUrl);
    $this->assertEquals('Christoph Rumpel', $channel->name);
    $this->assertStringStartsWith('Hi, I\'m Christoph Rumpel', $channel->description);
    $this->assertEquals('2010-01-12T19:15:29+00:00', $channel->onPlatformSince->toIso8601String());
    $this->assertEquals('AT', $channel->country);
    $this->assertEquals('https://yt3.ggpht.com/ytc/AAUvwniFZUkXnS4vDKY4lDohrpFsyu1V2AJwt4CFZGy25Q=s800-c-k-c0x00ffffff-no-rj', $channel->thumbnailUrl);
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
    $this->assertCount(3, $streams);

    /** @var \App\Services\YouTube\StreamData $finishedStream */
    $finishedStream = $streams->first();

    $this->assertEquals('gzqJZQyfkaI', $finishedStream->videoId);
    $this->assertEquals('Live coding new features for larastreamers.com', $finishedStream->title);
    $this->assertEquals("Christoph Rumpel created a nice new project: https://larastreamers.com/\nIn this stream, I'm going to add some features to Christoph's app.", $finishedStream->description);
    $this->assertEquals('https://i.ytimg.com/vi/gzqJZQyfkaI/maxresdefault.jpg', $finishedStream->thumbnailUrl);
    $this->assertEquals('2021-05-15T12:51:18+00:00', $finishedStream->publishedAt->toIso8601String());
    $this->assertEquals('2031-05-15T11:00:00+00:00', $finishedStream->plannedStart->toIso8601String());
    $this->assertEquals('2031-05-15T11:00:29+00:00', $finishedStream->actualStartTime->toIso8601String());
    $this->assertEquals('2031-05-15T11:30:29+00:00', $finishedStream->actualEndTime->toIso8601String());
    $this->assertEquals(StreamData::STATUS_FINISHED, $finishedStream->status);

    /** @var \App\Services\YouTube\StreamData $upcomingStream */
    $upcomingStream = $streams->last();

    $this->assertEquals('L3O1BbybSgw', $upcomingStream->videoId);
    $this->assertEquals('Casual Artisan Call #7', $upcomingStream->title);
    $this->assertEquals('https://i.ytimg.com/vi/L3O1BbybSgw/maxresdefault_live.jpg', $upcomingStream->thumbnailUrl);
    $this->assertEquals('2021-05-14T17:00:28+00:00', $upcomingStream->publishedAt->toIso8601String());
    $this->assertEquals('2021-05-21T09:00:00+00:00', $upcomingStream->plannedStart->toIso8601String());
    $this->assertNull($upcomingStream->actualStartTime);
    $this->assertNull($upcomingStream->actualEndTime);
    $this->assertEquals(StreamData::STATUS_UPCOMING, $upcomingStream->status);
});

it('uses real api key when needed', function () {
    $this->assertEquals('FAKE-YOUTUBE-KEY', config()->get('services.youtube.key'));

    $this->useRealYoutubeApi();

    config()->set('services.youtube.key', 'REAL-API-KEY');
    $this->assertEquals('REAL-API-KEY', config()->get('services.youtube.key'));
});
