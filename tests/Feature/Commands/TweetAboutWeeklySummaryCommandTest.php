<?php

namespace Tests\Feature\Commands;

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
        // Create streams for the beginning and end of last week
        Stream::factory()
            ->approved()
            ->finished()
            ->create(['actual_start_time' => Carbon::today()->subWeek()->startOfWeek()]);

        Stream::factory()
            ->approved()
            ->finished()
            ->create(['actual_start_time' => Carbon::today()->subWeek()->endOfWeek()->endOfDay()]);

        // Act
        $this->artisan('larastreamers:tweet-weekly-summary');

    	// Assert
        $this->twitterFake->assertTweetWasSent();
        $this->twitterFake->assertLastTweetMessageWas('There were 2 streams last week. Thanks to all the viewers 🙏🏻.');
    }
}
