<?php

namespace Tests\Feature;

use App\Console\Commands\TweetAboutLiveStreamsCommand;
use App\Models\Stream;
use App\Services\Youtube\StreamData;
use Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TweetTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_only_tweets_streams_that_are_live(): void
    {
        // Arrange
        Stream::factory()->create(['status' => StreamData::STATUS_LIVE]);

        // Assert
        $this->twitterFake->assertNoTweetsWereSent();

        // Act
        Artisan::call(TweetAboutLiveStreamsCommand::class);

        // Assert
        $this->twitterFake->assertTweetWasSent();
    }

    /** @test */
    public function it_correctly_sets_tweeted_at_timestamp(): void
    {
        // Arrange
        $streamToTweet = Stream::factory()->create(['status' => StreamData::STATUS_LIVE]);
        $streamDontTweet = Stream::factory()->create(['status' => StreamData::STATUS_UPCOMING]);

        // Assert
        $this->assertFalse($streamToTweet->hasBeenTweeted());
        $this->assertFalse($streamDontTweet->hasBeenTweeted());

        // Act
        Artisan::call(TweetAboutLiveStreamsCommand::class);

        // Assert
        $this->assertTrue($streamToTweet->refresh()->hasBeenTweeted());
        $this->assertFalse($streamDontTweet->refresh()->hasBeenTweeted());
    }

    /** @test */
    public function it_only_tweets_streams_that_are_going_live_once(): void
    {
        // Arrange
        Stream::factory()->create(['status' => StreamData::STATUS_LIVE, 'tweeted_at' => now()]);

        // Act
        Artisan::call(TweetAboutLiveStreamsCommand::class);

        // Assert
        $this->twitterFake->assertNoTweetsWereSent();
    }
}
