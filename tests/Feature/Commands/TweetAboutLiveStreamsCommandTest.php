<?php

namespace Tests\Feature\Commands;

use App\Console\Commands\TweetAboutLiveStreamsCommand;
use App\Console\Commands\TweetAboutUpcomingStreamsCommand;
use App\Models\Channel;
use App\Models\Stream;
use App\Services\Youtube\StreamData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TweetAboutLiveStreamsCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_tweets_streams_that_are_live(): void
    {
        // Arrange
        Stream::factory()->live()->create();

        // Assert
        $this->twitterFake->assertNoTweetsWereSent();

        // Act
        $this->artisan(TweetAboutLiveStreamsCommand::class);

        // Assert
        $this->twitterFake->assertTweetWasSent();
    }

    /** @test */
    public function it_does_not_tweet_streams_that_are_upcoming_or_finished(): void
    {
        // Arrange
        Stream::factory()->upcoming()->create(['scheduled_start_time' => now()->addMinutes(5)]);
        Stream::factory()->finished()->create(['scheduled_start_time' => now()->addMinutes(5)]);

        // Act
        $this->artisan(TweetAboutLiveStreamsCommand::class);

        // Assert
        $this->twitterFake->assertNoTweetsWereSent();
    }

    /** @test */
    public function it_checks_the_message_of_the_tweet(): void
    {
        // Arrange
        $stream = Stream::factory()->live()->create();
        $expectedStatus = "🔴 A new stream just started: $stream->title\nhttps://www.youtube.com/watch?v=$stream->youtube_id";

        // Act
        $this->artisan(TweetAboutLiveStreamsCommand::class);

        // Assert
        $this->twitterFake->assertLastTweetMessageWas($expectedStatus);
    }

    /** @test */
    public function it_adds_twitter_handle_to_streams_connected_to_a_channel(): void
    {
        // Arrange
        $stream = Stream::factory()
            ->for(Channel::factory()->create(['twitter_handle' => '@twitterUser']))
            ->create(['status' => StreamData::STATUS_LIVE]);

        $expectedStatus = "🔴 A new stream by @twitterUser just started: $stream->title\nhttps://www.youtube.com/watch?v=$stream->youtube_id";

        // Act
        $this->artisan(TweetAboutLiveStreamsCommand::class);

        // Assert
        $this->twitterFake->assertLastTweetMessageWas($expectedStatus);
    }

    /** @test */
    public function it_works_without_missing_twitter_handle_on_connected_channel(): void
    {
        // Arrange
        $stream = Stream::factory()
            ->live()
            ->for(Channel::factory())
            ->create();

        $expectedStatus = "🔴 A new stream just started: $stream->title\nhttps://www.youtube.com/watch?v=$stream->youtube_id";

        // Act
        $this->artisan(TweetAboutLiveStreamsCommand::class);

        // Assert
        $this->twitterFake->assertLastTweetMessageWas($expectedStatus);
    }

    /** @test */
    public function it_correctly_sets_tweeted_at_timestamp(): void
    {
        // Arrange
        $streamToTweet = Stream::factory()->live()->create();
        $streamDontTweet = Stream::factory()->upcoming()->create();

        // Assert
        $this->assertFalse($streamToTweet->hasBeenTweeted());
        $this->assertFalse($streamDontTweet->hasBeenTweeted());

        // Act
        $this->artisan(TweetAboutLiveStreamsCommand::class);

        // Assert
        $this->assertTrue($streamToTweet->refresh()->hasBeenTweeted());
        $this->assertFalse($streamDontTweet->refresh()->hasBeenTweeted());
    }

    /** @test */
    public function it_only_tweets_streams_that_are_going_live_once(): void
    {
        // Arrange
        Stream::factory()->live()->create(['tweeted_at' => now()]);

        // Act
        $this->artisan(TweetAboutLiveStreamsCommand::class);

        // Assert
        $this->twitterFake->assertNoTweetsWereSent();
    }
}
