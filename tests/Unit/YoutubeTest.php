<?php

namespace Tests\Unit;

use App\Facades\Youtube;
use App\Services\Youtube\StreamData;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class YoutubeTest extends TestCase
{
    /** @test */
    public function it_can_fetch_channel_details_from_youtube(): void
    {
        // Arrange
        Http::fake(fn() => Http::response($this->channelResponse()));

        // Act
        $channel = Youtube::channel('UCdtd5QYBx9MUVXHm7qgEpxA');

        // Assert
        $this->assertEquals('christophrumpel', $channel->slug);
        $this->assertEquals('Christoph Rumpel', $channel->name);
        $this->assertStringStartsWith('Hi, I\'m Christoph Rumpel', $channel->description);
        $this->assertEquals('2010-01-12T19:15:29+00:00', $channel->onPlatformSince->toIso8601String());
        $this->assertEquals('AT', $channel->country);
        $this->assertEquals('https://yt3.ggpht.com/ytc/AAUvwniFZUkXnS4vDKY4lDohrpFsyu1V2AJwt4CFZGy25Q=s800-c-k-c0x00ffffff-no-rj', $channel->thumbnailUrl);
    }

    /** @test */
    public function it_can_fetch_upcoming_streams_from_youtube(): void
    {
        // Arrange
        Http::fake([
            '*search*' => Http::response($this->upcomingStreamsResponse()),
            '*video*' => Http::response($this->videoResponse()),
        ]);

        // Act
        $streams = Youtube::upcomingStreams('UCNlUCA4VORBx8X-h-rXvXEg');

        // Assert
        $this->assertCount(3, $streams);

        /** @var \App\Services\Youtube\StreamData $finishedStream */
        $finishedStream = $streams->first();

        $this->assertEquals('gzqJZQyfkaI', $finishedStream->videoId);
        $this->assertEquals('Live coding new features for larastreamers.com', $finishedStream->title);
        $this->assertEquals("Christoph Rumpel created a nice new project: https://larastreamers.com/\nIn this stream, I'm going to add some features to Christoph's app.", $finishedStream->description);
        $this->assertEquals('https://i.ytimg.com/vi/gzqJZQyfkaI/maxresdefault.jpg', $finishedStream->thumbnailUrl);
        $this->assertEquals('2021-05-15T12:51:18+00:00', $finishedStream->publishedAt->toIso8601String());
        $this->assertEquals('2021-05-15T11:00:00+00:00', $finishedStream->plannedStart->toIso8601String());
        $this->assertEquals(StreamData::STATUS_NONE, $finishedStream->status);

        /** @var \App\Services\Youtube\StreamData $upcomingStream */
        $upcomingStream = $streams->last();

        $this->assertEquals('L3O1BbybSgw', $upcomingStream->videoId);
        $this->assertEquals('Casual Artisan Call #7', $upcomingStream->title);
        $this->assertEquals('https://i.ytimg.com/vi/L3O1BbybSgw/maxresdefault_live.jpg', $upcomingStream->thumbnailUrl);
        $this->assertEquals('2021-05-14T17:00:28+00:00', $upcomingStream->publishedAt->toIso8601String());
        $this->assertEquals('2021-05-21T09:00:00+00:00', $upcomingStream->plannedStart->toIso8601String());
        $this->assertEquals(StreamData::STATUS_UPCOMING, $upcomingStream->status);
    }

    /** @test */
    public function it_uses_real_api_key_when_needed(): void
    {
        $this->assertEquals('FAKE-YOUTUBE-KEY', config()->get('services.youtube.key'));

        $this->useRealYoutubeApi();

        config()->set('services.youtube.key', 'REAL-API-KEY');
        $this->assertEquals('REAL-API-KEY', config()->get('services.youtube.key'));
    }
}
