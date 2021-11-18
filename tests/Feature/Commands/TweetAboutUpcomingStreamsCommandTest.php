<?php

use App\Console\Commands\TweetAboutUpcomingStreamsCommand;
use App\Models\Channel;
use App\Models\Stream;
use Illuminate\Support\Carbon;
use Tests\TestCase;

uses(TestCase::class);

it('tweets streams that are upcoming', function () {
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
});

it('does not tweet streams that are live or finished', function () {
    // Arrange
    Stream::factory()->live()->startsWithinUpcomingTweetRange()->create();
    Stream::factory()->finished()->startsWithinUpcomingTweetRange()->create();

    // Act
    $this->artisan(TweetAboutUpcomingStreamsCommand::class);

    // Assert
    $this->twitterFake->assertNoTweetsWereSent();
});

it('checks the message of the tweet', function () {
    // Arrange
    $stream = Stream::factory()->upcoming()->startsWithinUpcomingTweetRange()->create();
    $expectedStatus = "🔴 A new stream is about to start: $stream->title. Join now!\n{$stream->url()}";

    // Act
    $this->artisan(TweetAboutUpcomingStreamsCommand::class);

    // Assert
    $this->twitterFake->assertLastTweetMessageWas($expectedStatus);
});

it('adds twitter handle to streams connected to a channel', function () {
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
});

it('works without missing twitter handle on connected channel', function () {
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
});

it('correctly sets upcoming tweeted at timestamp', function () {
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
});

it('does not send a tweet if the live tweet is sent', function () {
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
});

it('only tweets streams that are going live once', function () {
    // Arrange
    Stream::factory()->upcoming()->upcomingTweetWasSend()->create();

    // Act
    $this->artisan(TweetAboutUpcomingStreamsCommand::class);

    // Assert
    $this->twitterFake->assertNoTweetsWereSent();
});

it('does not tweet streams that are upcoming but already started', function () {
    // Arrange
    Stream::factory()->upcoming()->create([
        'scheduled_start_time' => now()->subSecond(),
    ]);

    // Act
    $this->artisan(TweetAboutUpcomingStreamsCommand::class);

    // Assert
    $this->twitterFake->assertNoTweetsWereSent();
});
