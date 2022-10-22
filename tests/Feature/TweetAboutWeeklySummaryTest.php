<?php

namespace Tests\Feature;

use App\Console\Commands\TweetAboutWeeklySummaryCommand;
use App\Models\Stream;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class TweetAboutWeeklySummaryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_tweets_weekly_summary(): void
    {
        // Arrange
        // Create streams for beginning and end of week
        Stream::factory()
            ->approved()
            ->finished()
            ->create(['scheduled_start_time' => Carbon::now()->subWeek()->startOfWeek()]);

        Stream::factory()
            ->approved()
            ->finished()
            ->create(['scheduled_start_time' => Carbon::now()->subWeek()->endOfWeek()]);

        // Act
        $this->artisan(TweetAboutWeeklySummaryCommand::class);

        // Assert
        $expectedTwitterStatus = 'Last week we had 2 streams. Thanks to all the streamers and viewers 🙏🏻';
        $this->twitterFake->assertTweetWasSent();
        $this->twitterFake->assertLastTweetMessageWas($expectedTwitterStatus);
    }

}
