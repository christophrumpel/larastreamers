<?php

use App\Console\Commands\TweetAboutWeeklySummaryCommand;
use App\Facades\Twitter;
use App\Models\Stream;
use Illuminate\Support\Carbon;

beforeEach(function() {
    Twitter::fake();
});

it('tweets weekly summary for a single stream', function() {
    // Arrange
    $startOfLastWeek = Carbon::today()->subWeek()->startOfWeek();

    Stream::factory()
        ->approved()
        ->finished()
        ->create(['actual_start_time' => $startOfLastWeek]);

    // Act
    $this->artisan(TweetAboutWeeklySummaryCommand::class);

    // Assert
    Twitter::assertTweetCount(1)
        ->assertLastTweet("Last week, there was 1 stream. 👏 Thanks to everyone who participated. 🙏🏻\n Find more Streams here: ".route('archive'));
});

it('tweets weekly summary for a multiple streams', function() {
    // Arrange
    $startOfLastWeek = Carbon::today()->subWeek()->startOfWeek();
    $endOfLastWeek = Carbon::today()->subWeek()->endOfWeek()->endOfDay();

    Stream::factory()
        ->approved()
        ->finished()
        ->create(['actual_start_time' => $startOfLastWeek]);

    Stream::factory()
        ->approved()
        ->finished()
        ->create(['actual_start_time' => $endOfLastWeek]);

    // Act
    $this->artisan(TweetAboutWeeklySummaryCommand::class);

    // Assert
    Twitter::assertTweetCount(1)
        ->assertLastTweet("Last week, there were 2 streams. 👏 Thanks to all the streamers and viewers. 🙏🏻\n Find more Streams here: ".route('archive'));
});

it('tweets weekly summary for streams without actual_start_time', function() {
    // Arrange
    $startOfLastWeek = Carbon::today()->subWeek()->startOfWeek();

    Stream::factory()
        ->approved()
        ->finished()
        ->create([
            'scheduled_start_time' => $startOfLastWeek,
            'actual_start_time' => null, // YouTube didn't populate this field
        ]);

    // Act
    $this->artisan(TweetAboutWeeklySummaryCommand::class);

    // Assert
    Twitter::assertTweetCount(1)
        ->assertLastTweet("Last week, there was 1 stream. 👏 Thanks to everyone who participated. 🙏🏻\n Find more Streams here: ".route('archive'));
});

it('does not tweet weekly summary when no streams given', function() {
    // Arrange
    $beforeLastWeek = Carbon::today()->subWeek()->startOfWeek()->subDay();
    $afterLastWeek = Carbon::today();

    Stream::factory()
        ->approved()
        ->finished()
        ->create(['actual_start_time' => $beforeLastWeek]);

    Stream::factory()
        ->approved()
        ->finished()
        ->create(['actual_start_time' => $afterLastWeek]);

    // Act
    $this->artisan(TweetAboutWeeklySummaryCommand::class)
        ->expectsOutput('There were no streams last week.');

    // Assert
    Twitter::assertNoTweetsSent();
});
