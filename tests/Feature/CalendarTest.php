<?php

namespace Tests\Feature;

use App\Models\Stream;
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
        Stream::factory()->create(['title' => 'Stream two years old', 'scheduled_start_time' => Carbon::now()->subYears(2), 'youtube_id' => '-2y']);
        Stream::factory()->create(['title' => 'Stream last year', 'scheduled_start_time' => Carbon::now()->subYear(), 'youtube_id' => '-1y']);
        Stream::factory()->create(['title' => 'Stream yesterday', 'scheduled_start_time' => Carbon::yesterday(), 'youtube_id' => '-1d']);
        Stream::factory()->create(['title' => 'Stream today', 'scheduled_start_time' => Carbon::now(), 'youtube_id' => 'now']);
        Stream::factory()->create(['title' => 'Stream tomorrow', 'scheduled_start_time' => Carbon::tomorrow(), 'youtube_id' => '1d']);
        Stream::factory()->create(['title' => 'Stream next week', 'scheduled_start_time' => Carbon::now()->addWeek(), 'youtube_id' => '1w']);
        Stream::factory()->create(['title' => 'Stream next month', 'scheduled_start_time' => Carbon::now()->addMonth(), 'youtube_id' => '1m']);
        Stream::factory()->create(['title' => 'Stream next year', 'scheduled_start_time' => Carbon::now()->addMonth(), 'youtube_id' => '1y']);

        // Act & Assert
        $this->get('/calendar.ics')
            ->assertHeader('Content-Type', 'text/calendar; charset=UTF-8')
            ->assertDontSee([
                'SUMMARY:Stream two years old',
                'DESCRIPTION:Stream two years old',
                'https://www.youtube.com/watch?v=-2y',
            ])
            ->assertSeeInOrder([
                'SUMMARY:Stream last year',
                'DESCRIPTION:Stream last year',
                'https://www.youtube.com/watch?v=-1y',
            ])
            ->assertSeeInOrder([
                'SUMMARY:Stream yesterday',
                'DESCRIPTION:Stream yesterday',
                'https://www.youtube.com/watch?v=-1d',
            ])
            ->assertSeeInOrder([
                'SUMMARY:Stream today',
                'DESCRIPTION:Stream today',
                'https://www.youtube.com/watch?v=now',
            ])
            ->assertSeeInOrder([
                'SUMMARY:Stream tomorrow',
                'DESCRIPTION:Stream tomorrow',
                'https://www.youtube.com/watch?v=1d',
            ])
            ->assertSeeInOrder([
                'SUMMARY:Stream next week',
                'DESCRIPTION:Stream next week',
                'https://www.youtube.com/watch?v=1w',
            ])
            ->assertSeeInOrder([
                'SUMMARY:Stream next month',
                'DESCRIPTION:Stream next month',
                'https://www.youtube.com/watch?v=1m',
            ])
            ->assertSeeInOrder([
                'SUMMARY:Stream next year',
                'DESCRIPTION:Stream next year',
                'https://www.youtube.com/watch?v=1y',
            ]);

        // TODOS: We cannot test the description at the end of the DESCRIPTION field
        // after a specific length, the generated output cuts of the the string
        // so we cannot assert this string anymore
        // that's why the description is not in the string.
        /** @see \Spatie\IcalendarGenerator\Builders\ComponentBuilder::chipLine() */
    }

    /** @test */
    public function it_can_download_one_calendar_item(): void
    {
        $stream = Stream::factory()->create([
            'title' => 'Single Stream',
            'channel_title' => 'My Channel',
            'scheduled_start_time' => Carbon::now(),
            'youtube_id' => '1234',
        ]);

        $this
            ->get(route('calendar.ics.stream', $stream))
            ->assertHeader('Content-Type', 'text/calendar; charset=UTF-8')
            ->assertSeeInOrder([
                'SUMMARY:Single Stream',
                'DESCRIPTION:Single Stream\nMy Channel\nhttps://www.youtube.com/watch?v=1234',
            ]);
    }
}
