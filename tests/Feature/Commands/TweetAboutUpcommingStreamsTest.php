<?php

namespace Tests\Feature\Commands;

use App\Console\Commands\TweetAboutUpcomingStreamsCommand;
use App\Models\Channel;
use App\Models\Stream;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TweetAboutUpcommingStreamsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_only_tweets_streams_that_are_upcoming(): void
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
    public function it_checks_the_message_of_the_tweet(): void
    {
        // Arrange
        $stream         = Stream::factory()->upcoming()->create(['scheduled_start_time' => now()->addMinutes(5)]);
        $expectedStatus = "🔴 A new stream is about to start: $stream->title. Join in now!\n{$stream->url()}";

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

        $expectedStatus = "🔴 A new stream by @twitterUser is about to start: $stream->title. Join in now!\n{$stream->url()}";

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

        $expectedStatus = "🔴 A new stream is about to start: $stream->title. Join in now!\n{$stream->url()}";

        // Act
         $this->artisan(TweetAboutUpcomingStreamsCommand::class);

        // Assert
        $this->twitterFake->assertLastTweetMessageWas($expectedStatus);
    }

    /** @test */
    public function it_correctly_sets_announcement_tweeted_at_timestamp(): void
    {
        // Arrange
        $streamToTweet       = Stream::factory()->upcoming()->create(['scheduled_start_time' => now()->addMinutes(5)]);
        $streamDontTweet6Min = Stream::factory()->upcoming()->create(['scheduled_start_time' => now()->addMinutes(6)]);
        $streamDontTweetLive = Stream::factory()->live()->create();

        // Assert
        $this->assertNull($streamToTweet->announcement_tweeted_at);
        $this->assertNull($streamDontTweet6Min->announcement_tweeted_at);
        $this->assertNull($streamDontTweetLive->announcement_tweeted_at);

        // Act
        $this->artisan(TweetAboutUpcomingStreamsCommand::class)
            ->expectsOutput('1 tweets sent')
            ->assertExitCode(0);

        // Assert
        $this->assertNotNull($streamToTweet->refresh()->announcement_tweeted_at);
        $this->assertNull($streamDontTweet6Min->refresh()->announcement_tweeted_at);
        $this->assertNull($streamDontTweetLive->refresh()->announcement_tweeted_at);
    }

    /** @test */
    public function it_only_tweets_streams_that_are_going_live_once(): void
    {
        // Arrange
        Stream::factory()->upcoming()->create(['scheduled_start_time' => now()->addMinutes(5), 'announcement_tweeted_at' => now()]);

        // Act
         $this->artisan(TweetAboutUpcomingStreamsCommand::class);

        // Assert
        $this->twitterFake->assertNoTweetsWereSent();
    }
}
