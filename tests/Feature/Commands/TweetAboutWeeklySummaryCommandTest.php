<?php

namespace Tests\Feature\Commands;

use App\Console\Commands\TweetAboutWeeklySummaryCommand;
use App\Models\Stream;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TweetAboutWeeklySummaryCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_tweets_weekly_summary(): void
    {
        // Arrange
        $startOfWeek = Carbon::now()->subWeek()->startOfWeek();
        $endOfWeek = Carbon::now()->subWeek()->endOfWeek();

        Stream::factory()
            ->approved()
            ->finished()
            ->create(['scheduled_start_time' => $startOfWeek]);

        Stream::factory()
            ->approved()
            ->finished()
            ->create(['scheduled_start_time' => $endOfWeek]);

        // Act
        $this->artisan(TweetAboutWeeklySummaryCommand::class);

        // Assert
        $expectedTwitterStatus = 'Last week we had 2 streams. Thanks to all the streamers and viewers 🙏🏻';
        $this->twitterFake->assertTweetWasSent();
        $this->twitterFake->assertLastTweetMessageWas($expectedTwitterStatus);
    }

    /** @test */
    public function it_does_not_tweet_weekly_summary_if_no_streams_given(): void
    {
        // Arrange
        $beforeLastWeek = Carbon::now()->subWeek()->startOfWeek()->subDay();
        $afterLastWeek = Carbon::today();

        Stream::factory()
            ->approved()
            ->finished()
            ->create(['scheduled_start_time' => $afterLastWeek]);

        Stream::factory()
            ->approved()
            ->finished()
            ->create(['scheduled_start_time' => $beforeLastWeek]);

        // Act & Assert
        $this->artisan(TweetAboutWeeklySummaryCommand::class)
            ->expectsOutput('There were no streams last week.')
            ->assertExitCode(0);
    }


}
