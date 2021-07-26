<?php

namespace Tests\Feature\Commands;

use App\Console\Commands\TweetAboutUpcomingStreamsCommand;
use App\Models\Channel;
use App\Models\Stream;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class TweetAboutUpcomingStreamsCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_tweets_streams_that_are_upcoming(): void
    {
        // Arrange
        Stream::factory()->upcoming()->create(['scheduled_start_time' => now()->addMinutes(5)]);

        // Assert
        $this->twitterFake->assertNoTweetsWereSent();

        // Act
        $this->artisan(TweetAboutUpcomingStreamsCommand::class);

        // Assert
        $this->twitterFake->assertTweetWasSent();
    }

    /** @test */
    public function it_does_not_tweet_streams_that_are_live_or_finished(): void
    {
        // Arrange
        Stream::factory()->live()->create(['scheduled_start_time' => now()->addMinutes(5)]);
        Stream::factory()->finished()->create(['scheduled_start_time' => now()->addMinutes(5)]);

        // Act
        $this->artisan(TweetAboutUpcomingStreamsCommand::class);

        // Assert
        $this->twitterFake->assertNoTweetsWereSent();
    }

    /** @test */
    public function it_checks_the_message_of_the_tweet(): void
    {
        // Arrange
        $stream = Stream::factory()->upcoming()->create(['scheduled_start_time' => now()->addMinutes(5)]);
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
            ->create(['scheduled_start_time' => now()->addMinutes(5)]);

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
            ->for(Channel::factory())
            ->create(['scheduled_start_time' => now()->addMinutes(5)]);

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
        $upcomingStreamToTweet = Stream::factory()->upcoming()->create(['scheduled_start_time' => now()->addMinutes(5)]);
        $upcomingStreamNotToTweet = Stream::factory()->upcoming()->create(['scheduled_start_time' => now()->addMinutes(6)]);
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
            ->create(['scheduled_start_time' => now()->addMinutes(5)]);

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
}
