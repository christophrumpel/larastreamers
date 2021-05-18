<?php

namespace Tests\Feature;

use App\Models\Stream;
use App\Services\Youtube\StreamData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\TestTime\TestTime;
use Tests\TestCase;

class TweetTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_only_tweets_streams_that_are_going_live(): void
    {
        // Arrange
        TestTime::freeze();
        $stream = Stream::factory()->create([
            'status' => StreamData::STATUS_UPCOMING,
        ]);

        // Assert
        $this->twitterFake->assertNoTweetsWereSent();

        // Act
        $stream->update(['status' => StreamData::STATUS_LIVE]);

        // Assert
        $this->twitterFake->assertTweetWasSent();
    }

    /** @test */
    public function it_correctly_sets_tweeted_at_timestamp(): void
    {
        // Arrange
        TestTime::freeze();
        $stream = Stream::factory()->create([
            'status' => StreamData::STATUS_UPCOMING,
        ]);

        // Assert
        $this->assertFalse($stream->refresh()->hasBeenTweeted());

        // Act
        $stream->update(['status' => StreamData::STATUS_LIVE]);

        // Assert
        $this->assertTrue($stream->refresh()->hasBeenTweeted());
    }

    /** @test */
    public function it_only_tweets_streams_that_are_going_live_once(): void
    {
        // Arrange
        TestTime::freeze();
        $stream = Stream::factory()->create([
            'status' => StreamData::STATUS_LIVE,
        ]);

        // Assert
        $this->assertTrue($stream->refresh()->hasBeenTweeted());

        // Arrange
        $originallyTweetedAt = $stream->tweeted_at;
        TestTime::addMinute();
        $stream->update(['status' => StreamData::STATUS_LIVE]);

        // Assert
        $this->assertEquals($stream->refresh()->tweeted_at->timestamp, $originallyTweetedAt->timestamp);
    }
}
