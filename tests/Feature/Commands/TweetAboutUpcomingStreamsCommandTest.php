<?php

namespace Tests\Feature\Commands;

use App\Console\Commands\TweetAboutUpcomingStreamsCommand;
use App\Models\Channel;
use App\Models\Stream;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class TweetAboutUpcomingStreamsCommandTest extends TestCase
{
    /** @test */
    public function it_tweets_streams_that_are_upcoming(): void
    {
        // Arrange
        $stream = Stream::factory()
            ->upcoming()
            ->startsWithinUpcomingTweetRange()
            ->create();

        // Assert
        $this->twitterFake->assertNoTweetsWereSent();
        $this->assertFalse($stream->tweetStreamIsUpcomingWasSend());

        // Act
        $this->artisan(TweetAboutUpcomingStreamsCommand::class);

        // Assert
        $this->twitterFake->assertTweetWasSent();
        $this->assertTrue($stream->refresh()->tweetStreamIsUpcomingWasSend());
    }

    /** @test */
    public function it_does_not_tweet_streams_that_are_live_or_finished(): void
    {
        // Arrange
        Stream::factory()->live()->startsWithinUpcomingTweetRange()->create();
        Stream::factory()->finished()->startsWithinUpcomingTweetRange()->create();

        // Act
        $this->artisan(TweetAboutUpcomingStreamsCommand::class);

        // Assert
        $this->twitterFake->assertNoTweetsWereSent();
    }

    /** @test */
    public function it_checks_the_message_of_the_tweet(): void
    {
        // Arrange
        $stream = Stream::factory()->upcoming()->startsWithinUpcomingTweetRange()->create();
        $expectedStatus = "🔴 A new stream is about to start: $stream->title. Join now!\n{$stream->url()}";

        // Act
        $this->artisan(TweetAboutUpcomingStreamsCommand::class);

        // Assert
        $this->twitterFake->assertLastTweetMessageWas($expectedStatus);
    }

    /** @test */
    public function it_adds_twitter_handle_to_streams_connected_to_a_channel(): void
    {
        // Arrange
        $stream = Stream::factory()
            ->upcoming()
            ->for(Channel::factory()->create(['twitter_handle' => '@twitterUser']))
            ->startsWithinUpcomingTweetRange()
            ->create();

        $expectedStatus = "🔴 A new stream by @twitterUser is about to start: $stream->title. Join now!\n{$stream->url()}";

        // Act
        $this->artisan(TweetAboutUpcomingStreamsCommand::class);

        // Assert
        $this->twitterFake->assertLastTweetMessageWas($expectedStatus);
    }

    /** @test */
    public function it_works_without_missing_twitter_handle_on_connected_channel(): void
    {
        // Arrange
        $stream = Stream::factory()
            ->upcoming()
            ->for(Channel::factory()->create(['twitter_handle' => null]))
            ->startsWithinUpcomingTweetRange()
            ->create();

        $expectedStatus = "🔴 A new stream is about to start: $stream->title. Join now!\n{$stream->url()}";

        // Act
        $this->artisan(TweetAboutUpcomingStreamsCommand::class);

        // Assert
        $this->twitterFake->assertLastTweetMessageWas($expectedStatus);
    }

    /** @test */
    public function it_correctly_sets_upcoming_tweeted_at_timestamp(): void
    {
        // Arrange
        Carbon::setTestNow(now());
        $upcomingStreamToTweet = Stream::factory()->upcoming()->startsWithinUpcomingTweetRange()->create();
        $upcomingStreamNotToTweet = Stream::factory()->upcoming()->startsOutsideUpcomingTweetRange()->create();
        $liveStreamNotToTweet = Stream::factory()->live()->create();

        // Assert
        $this->assertNull($upcomingStreamToTweet->upcoming_tweeted_at);
        $this->assertNull($upcomingStreamNotToTweet->upcoming_tweeted_at);
        $this->assertNull($liveStreamNotToTweet->upcoming_tweeted_at);

        // Act
        $this->artisan(TweetAboutUpcomingStreamsCommand::class)
            ->expectsOutput('1 tweets sent')
            ->assertExitCode(0);

        // Assert
        tap($upcomingStreamToTweet->fresh(), function($upcomingStreamToTweet) {
            $this->assertNotNull($upcomingStreamToTweet->upcoming_tweeted_at);
            $this->assertInstanceOf(Carbon::class, $upcomingStreamToTweet->upcoming_tweeted_at);
            $this->assertSame((string) now(), (string) $upcomingStreamToTweet->upcoming_tweeted_at);
            $this->assertTrue($upcomingStreamToTweet->tweetStreamIsUpcomingWasSend());
        });

        $this->assertNull($upcomingStreamNotToTweet->refresh()->upcoming_tweeted_at);
        $this->assertNull($liveStreamNotToTweet->refresh()->upcoming_tweeted_at);
    }

    /** @test */
    public function it_does_not_send_a_tweet_if_the_live_tweet_is_sent(): void
    {
        // Arrange
        Carbon::setTestNow(now());

        $stream = Stream::factory()
            ->upcoming()
            ->liveTweetWasSend()
            ->startsWithinUpcomingTweetRange()
            ->create();

        // Assert
        $this->assertNull($stream->upcoming_tweeted_at);

        // Act
        $this->artisan(TweetAboutUpcomingStreamsCommand::class)
            ->assertExitCode(0);

        // Assert
        $this->twitterFake->assertNoTweetsWereSent();
    }

    /** @test */
    public function it_only_tweets_streams_that_are_going_live_once(): void
    {
        // Arrange
        Stream::factory()->upcoming()->upcomingTweetWasSend()->create();

        // Act
        $this->artisan(TweetAboutUpcomingStreamsCommand::class);

        // Assert
        $this->twitterFake->assertNoTweetsWereSent();
    }

    /** @test */
    public function it_does_not_tweet_streams_that_are_upcoming_but_already_started(): void
    {
        // Arrange
        Stream::factory()->upcoming()->create([
            'scheduled_start_time' => now()->subSecond(),
        ]);

        // Act
        $this->artisan(TweetAboutUpcomingStreamsCommand::class);

        // Assert
        $this->twitterFake->assertNoTweetsWereSent();
    }
}
