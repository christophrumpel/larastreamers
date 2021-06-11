<?php

namespace Tests\Feature;

use App\Actions\PrepareStreams;
use App\Models\Stream;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PrepareStreamsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
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
        $preparedStreams = $prepareStreamsAction->handle($streams);

        // Assert
        $this->assertEquals('Today', $preparedStreams->keys()[0]);
        $this->assertEquals('Tomorrow', $preparedStreams->keys()[1]);
        $this->assertEquals(Carbon::tomorrow()->addDay()->format('D d.m.Y'), $preparedStreams->keys()[2]);
    }

    /** @test */
    public function it_orders_streams_from_current_to_upcoming(): void
    {
        $this->travelTo(Carbon::parse('2021-06-11 00:00'));

        // Arrange
        $streams = Stream::factory()->count(3)
            ->state(new Sequence(
                ['scheduled_start_time' => Carbon::today()],
                ['scheduled_start_time' => Carbon::tomorrow()],
                ['scheduled_start_time' => Carbon::tomorrow()->addDay()],
            ))->create();

        $prepareStreamsAction = new PrepareStreams;

        // Act
        $preparedStreams = $prepareStreamsAction->handle($streams);

        $this->assertEquals([
            'Today',
            'Tomorrow',
            'Sun 13.06.2021'
        ], $preparedStreams->keys()->toArray());
    }

    /** @test */
    public function it_orders_past_streams_from_latest_to_oldest(): void
    {
        $this->travelTo(Carbon::parse('2021-06-11 00:00'));

        // Arrange
        $streams = Stream::factory()->count(3)
            ->state(new Sequence(
                ['scheduled_start_time' => Carbon::yesterday()],
                ['scheduled_start_time' => Carbon::yesterday()->subDay()],
                ['scheduled_start_time' => Carbon::yesterday()->subDays(2)],
            ))->create();

        $prepareStreamsAction = new PrepareStreams;

        // Act
        $preparedStreams = $prepareStreamsAction
            ->fromLatestToOldest()
            ->handle($streams);

        $this->assertEquals([
            'Yesterday',
            'Wed 09.06.2021',
            'Tue 08.06.2021'
        ], $preparedStreams->keys()->toArray());
    }

}
