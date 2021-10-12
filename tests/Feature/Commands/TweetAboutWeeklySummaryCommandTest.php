<?php

namespace Tests\Feature\Commands;

use App\Console\Commands\TweetAboutWeeklySummaryCommand;
use App\Models\Stream;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class TweetAboutWeeklySummaryCommandTest extends TestCase
{
    /** @test */
    public function it_tweets_weekly_summary(): void
    {
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
        $this->twitterFake->assertTweetWasSent();
        $this->twitterFake->assertLastTweetMessageWas("There were 2 streams last week. 👏 Thanks to all the streamers and viewers. 🙏🏻\n Find them here: ".route('archive'));
    }

    /** @test */
    public function it_does_not_tweet_weekly_summary_when_no_streams_given(): void
    {
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
        $this->twitterFake->assertNoTweetsWereSent();
    }
}
