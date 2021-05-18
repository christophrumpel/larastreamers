<?php

namespace Tests\Feature;

use App\Models\Stream;
use App\Services\Youtube\StreamData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CalendarTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_shows_all_streams_in_calendar(): void
    {
        // Arrange
        $scheduledStartTime1 = Carbon::yesterday();
        $scheduledStartTime2 = Carbon::today();
        $scheduledStartTime3 = Carbon::now()->addDays();
        $scheduledStartTime4 = Carbon::now()->addDays(2);
        $scheduledStartTime5 = Carbon::now()->addDays(3);
        Stream::factory()->create(['title' => 'Stream #1', 'scheduled_start_time' => $scheduledStartTime1, 'youtube_id' => '1111', 'status' => StreamData::STATUS_NONE]);
        Stream::factory()->create(['title' => 'Stream #2', 'scheduled_start_time' => $scheduledStartTime2, 'youtube_id' => '2222']);
        Stream::factory()->create(['title' => 'Stream #3', 'scheduled_start_time' => $scheduledStartTime3, 'youtube_id' => '3333']);
        Stream::factory()->create(['title' => 'Stream #4', 'scheduled_start_time' => $scheduledStartTime4, 'youtube_id' => '4444']);
        Stream::factory()->create(['title' => 'Stream #5', 'scheduled_start_time' => $scheduledStartTime5, 'youtube_id' => '5555']);

        // Act & Assert
        $this->get('/calendar.ics')
            ->assertHeader('Content-Type', 'text/calendar; charset=UTF-8')
            ->assertDontSee([
                'SUMMARY:Stream #1',
                'DESCRIPTION:Stream #1',
                'https://www.youtube.com/watch?v=1111',
            ])
            ->assertSeeInOrder([
                'SUMMARY:Stream #2',
                'DESCRIPTION:Stream #2',
                'https://www.youtube.com/watch?v=2222',
            ])
            ->assertSeeInOrder([
                'SUMMARY:Stream #3',
                'DESCRIPTION:Stream #3',
                'https://www.youtube.com/watch?v=3333',
            ])
            ->assertSeeInOrder([
                'SUMMARY:Stream #4',
                'DESCRIPTION:Stream #4',
                'https://www.youtube.com/watch?v=4444',
            ])
            ->assertSeeInOrder([
                'SUMMARY:Stream #5',
                'DESCRIPTION:Stream #5',
                'https://www.youtube.com/watch?v=5555',
            ]);
    }

    /** @test */
    public function it_can_download_one_calendar_item(): void
    {
        $stream = Stream::factory()->create([
            'title' => 'Stream',
            'scheduled_start_time' => Carbon::now(),
            'youtube_id' => '2222'
        ]);

        $this
            ->get(route('calendar.ics.stream', $stream))
            ->assertHeader('Content-Type', 'text/calendar; charset=UTF-8');
    }
}
