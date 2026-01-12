<?php

use App\Console\Commands\TweetAboutLiveStreamsCommand;
use App\Facades\Twitter;
use App\Models\Channel;
use App\Models\Stream;
use App\Services\YouTube\StreamData;

beforeEach(function() {
    Twitter::fake();
});

it('tweets streams that are live', function() {
    // Arrange
    Stream::factory()->live()->create();

    // Assert
    Twitter::assertNoTweetsSent();

    // Act
    $this->artisan(TweetAboutLiveStreamsCommand::class);

    // Assert
    Twitter::assertTweetCount(1);
});

it('does not tweet streams that are upcoming or finished', function() {
    // Arrange
    Stream::factory()->upcoming()->create(['scheduled_start_time' => now()->addMinutes(5)]);
    Stream::factory()->finished()->create(['scheduled_start_time' => now()->addMinutes(5)]);

    // Act
    $this->artisan(TweetAboutLiveStreamsCommand::class);

    // Assert
    Twitter::assertNoTweetsSent();
});

it('checks the message of the tweet', function() {
    // Arrange
    $stream = Stream::factory()->live()->create();
    $expectedStatus = "🔴 A new stream just started: $stream->title\nhttps://www.youtube.com/watch?v=$stream->youtube_id";

    // Act
    $this->artisan(TweetAboutLiveStreamsCommand::class);

    // Assert
    Twitter::assertLastTweet($expectedStatus);
});

it('adds twitter handle to streams connected to a channel', function() {
    // Arrange
    $stream = Stream::factory()
        ->for(Channel::factory()->create(['twitter_handle' => '@twitterUser']))
        ->create(['status' => StreamData::STATUS_LIVE]);

    $expectedStatus = "🔴 A new stream by @twitterUser just started: $stream->title\nhttps://www.youtube.com/watch?v=$stream->youtube_id";

    // Act
    $this->artisan(TweetAboutLiveStreamsCommand::class);

    // Assert
    Twitter::assertLastTweet($expectedStatus);
});

it('works without missing twitter handle on connected channel', function() {
    // Arrange
    $stream = Stream::factory()
        ->live()
        ->for(Channel::factory()->create(['twitter_handle' => null]))
        ->create();

    $expectedStatus = "🔴 A new stream just started: $stream->title\nhttps://www.youtube.com/watch?v=$stream->youtube_id";

    // Act
    $this->artisan(TweetAboutLiveStreamsCommand::class);

    // Assert
    Twitter::assertLastTweet($expectedStatus);
});

it('correctly sets tweeted at timestamp', function() {
    // Arrange
    $streamToTweet = Stream::factory()->live()->create();
    $streamDontTweet = Stream::factory()->upcoming()->create();

    // Assert
    expect($streamToTweet->tweetStreamIsLiveWasSend())->toBeFalse();
    expect($streamDontTweet->tweetStreamIsLiveWasSend())->toBeFalse();

    // Act
    $this->artisan(TweetAboutLiveStreamsCommand::class);

    // Assert
    expect($streamToTweet->refresh()->tweetStreamIsLiveWasSend())->toBeTrue();
    expect($streamDontTweet->refresh()->tweetStreamIsLiveWasSend())->toBeFalse();
});

it('only tweets streams that are going live once', function() {
    // Arrange
    Stream::factory()
        ->live()
        ->liveTweetWasSend()
        ->create();

    // Act
    $this->artisan(TweetAboutLiveStreamsCommand::class);

    // Assert
    Twitter::assertNoTweetsSent();
});

it('does not tweet streams that were scheduled more than an hour ago', function() {
    // Arrange
    Stream::factory()
        ->live()
        ->create(['scheduled_start_time' => now()->subHours(2)]);

    // Act
    $this->artisan(TweetAboutLiveStreamsCommand::class);

    // Assert
    Twitter::assertNoTweetsSent();
});
