<?php

namespace Tests\Feature;

use App\Actions\PrepareStreams;
use App\Models\Stream;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PrepareStreamsTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_groups_streams_by_date(): void
    {
    	// Arrange
        $streams = Stream::factory()->count(3)
            ->state(new Sequence(
                ['scheduled_start_time' => Carbon::today()],
                ['scheduled_start_time' => Carbon::tomorrow()],
                ['scheduled_start_time' => Carbon::tomorrow()->addDay()],
            ))->create();

        $prepareStreamsAction = new PrepareStreams;

        // Act
        $preparedStreams = $prepareStreamsAction->handle($streams, 'Europe/Vienna');

    	// Assert
        $this->assertEquals('Today', $preparedStreams->keys()[0]);
        $this->assertEquals('Tomorrow', $preparedStreams->keys()[1]);
        $this->assertEquals(Carbon::tomorrow()->addDay()->format('D d.m.Y'), $preparedStreams->keys()[2]);
    }

}
