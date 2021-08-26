<?php

namespace Tests\Feature\Commands;

use App\Console\Commands\TweetWeeklyStreamsSummary;
use App\Models\Stream;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TweetWeeklySummaryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_tweets_weekly_summary(): void
    {
        // Arrange
        $startOfLastWeek = Stream::factory()
            ->approved()
            ->finished()
            ->create(['scheduled_start_time' => Carbon::now()->subDays(7)->startOfDay()]);

        $endOfLastWeek = Stream::factory()
            ->approved()
            ->finished()
            ->create(['scheduled_start_time' => Carbon::yesterday()->endOfDay()]);


        // Act
        $this->artisan(TweetWeeklyStreamsSummary::class);

        // Assert
        $expectedTwitterStatus = 'Last week we had 2 streams.';
        $this->twitterFake->assertTweetWasSent();
        $this->twitterFake->assertLastTweetMessageWas($expectedTwitterStatus);
    }

    /** @test */
    public function it_does_not_tweet_weekly_summary_if_no_streams_given(): void
    {
        Carbon::setTestNow(Carbon::createFromFormat('d.m.Y', '23.08.2021',));
        // Arrange
        $today = Stream::factory()
            ->approved()
            ->finished()
            ->create(['scheduled_start_time' => Carbon::today()]);

        $longAgo = Stream::factory()
            ->approved()
            ->finished()
            ->create(['scheduled_start_time' => Carbon::now()->subDays(8)]);


        // Act
        $this->artisan(TweetWeeklyStreamsSummary::class)
            ->expectsOutput('There were no streams last week.')
            ->assertExitCode(0);
    }


}
