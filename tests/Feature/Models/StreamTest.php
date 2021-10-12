<?php

namespace Tests\Feature\Models;

use App\Models\Stream;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class StreamTest extends TestCase
{
    /** @test */
    public function it_only_gives_approved_streams(): void
    {
        // Arrange
        Stream::factory()->notApproved()->create();
        Stream::factory()->approved()->create();

        // Act
        $streams = Stream::approved()->get();

        // Assert
        $this->assertCount(1, $streams);
    }

    /** @test */
    public function it_gets_next_upcoming_stream(): void
    {
        // Arrange
        Stream::factory()
            ->upcoming()
            ->create(['scheduled_start_time' => Carbon::tomorrow()->addDay()]);

        $expectedStream = Stream::factory()
            ->upcoming()
            ->create(['scheduled_start_time' => Carbon::tomorrow()]);

        // Act
        $actualStream = Stream::getNextUpcomingOrLive();

        // Assert
        $this->assertEquals($expectedStream->id, $actualStream->id);
    }

    /** @test */
    public function it_gets_next_live_stream_before_upcoming(): void
    {
        // Arrange
        Stream::factory()
            ->upcoming()
            ->create(['scheduled_start_time' => Carbon::tomorrow()->addDay()]);

        $expectedStream = Stream::factory()
            ->live()
            ->create(['scheduled_start_time' => Carbon::tomorrow()]);

        // Act
        $actualStream = Stream::getNextUpcomingOrLive();

        // Assert
        $this->assertEquals($expectedStream->id, $actualStream->id);
    }
}
