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
        Stream::factory()->create(['title' => 'Stream #1', 'channel_title' => 'Channel 1', 'description' => 'Description 1', 'scheduled_start_time' => Carbon::yesterday(), 'youtube_id' => '1111', 'status' => StreamData::STATUS_NONE]);
        Stream::factory()->create(['title' => 'Stream #2', 'channel_title' => 'Channel 2', 'description' => 'Description 2', 'scheduled_start_time' => Carbon::today(), 'youtube_id' => '2222']);
        Stream::factory()->create(['title' => 'Stream #3', 'channel_title' => 'Channel 3', 'description' => 'Description 3', 'scheduled_start_time' => Carbon::now()->addDays(), 'youtube_id' => '3333']);
        Stream::factory()->create(['title' => 'Stream #4', 'channel_title' => 'Channel 4', 'description' => 'Description 4', 'scheduled_start_time' => Carbon::now()->addDays(2), 'youtube_id' => '4444']);
        Stream::factory()->create(['title' => 'Stream #5', 'channel_title' => 'Channel 5', 'description' => 'Description 5', 'scheduled_start_time' => Carbon::now()->addDays(3), 'youtube_id' => '5555']);

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
                'DESCRIPTION:Stream #2\nChannel 2\nhttps://www.youtube.com/watch?v=2222\n',
            ])
            ->assertSeeInOrder([
                'SUMMARY:Stream #3',
                'DESCRIPTION:Stream #3\nChannel 3\nhttps://www.youtube.com/watch?v=3333\n',
            ])
            ->assertSeeInOrder([
                'SUMMARY:Stream #4',
                'DESCRIPTION:Stream #4\nChannel 4\nhttps://www.youtube.com/watch?v=4444\n',
            ])
            ->assertSeeInOrder([
                'SUMMARY:Stream #5',
                'DESCRIPTION:Stream #5\nChannel 5\nhttps://www.youtube.com/watch?v=5555\n',
            ]);

        // TODOS: We cannot test the description at the end of the DESCRIPTION field
        // after a specific length, the generated output cuts of the the string
        // so we cannot assert this string anymore
        // that's why the description is not in the string.
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
