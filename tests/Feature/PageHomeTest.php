<?php

namespace Tests\Feature;

use App\Models\Stream;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PageHomeTest extends TestCase
{

    use RefreshDatabase;

    /** @test **/
    public function it_shows_given_streams_on_home_page(): void
    {
    	// Arrange
        $scheduledStartTime1 = Carbon::now()->addDays();
        $scheduledStartTime2 = Carbon::now()->addDays(2);
        $scheduledStartTime3 = Carbon::now()->addDays(3);
    	Stream::factory()->create(['title' => 'Stream #1', 'scheduled_start_time' => $scheduledStartTime1, 'youtube_id' => '1234', 'channel_title' => 'My Channel']);
    	Stream::factory()->create(['title' => 'Stream #2', 'scheduled_start_time' => $scheduledStartTime2, 'youtube_id' => '12345']);
    	Stream::factory()->create(['title' => 'Stream #3', 'scheduled_start_time' => $scheduledStartTime3, 'youtube_id' => '123456']);

    	// Act & Assert
        $this->get('/?timezone=Europe/Vienna')
            ->assertSee('Stream #1')
            ->assertSee('https://www.youtube.com/watch?v=1234')
            ->assertSee('My Channel')
            ->assertSee('Stream #2')
            ->assertSee('https://www.youtube.com/watch?v=12345')
            ->assertSee('Stream #3')
            ->assertSee('https://www.youtube.com/watch?v=123456');
    }

    /** @test **/
    public function it_shows_from_closest_to_farthest(): void
    {
        // Arrange
        $scheduledStartTime1 = Carbon::tomorrow()->format('d.m.Y');
        $scheduledStartTime2 = Carbon::tomorrow()->addDay()->format('d.m.Y');
        $scheduledStartTime3 = Carbon::tomorrow()->addDays(2)->format('d.m.Y');
        Stream::factory()->create(['title' => 'Stream #1', 'scheduled_start_time' => $scheduledStartTime1]);
        Stream::factory()->create(['title' => 'Stream #2', 'scheduled_start_time' => $scheduledStartTime2]);
        Stream::factory()->create(['title' => 'Stream #3', 'scheduled_start_time' => $scheduledStartTime3]);

        // Act & Assert
        $this->get('/')
            ->assertSeeInOrder(['Stream #1', 'Stream #2', 'Stream #3']);
    }

    /** @test **/
    public function it_shows_unique_names_for_today_and_tomorrow_instead_of_whole_date(): void
    {
        $this->withoutExceptionHandling();
        // Arrange
        $scheduledStartTime1 = Carbon::today()->format('d.m.Y');
        $scheduledStartTime2 = Carbon::tomorrow()->format('d.m.Y');
        Stream::factory()->create(['title' => 'Stream #1', 'scheduled_start_time' => $scheduledStartTime1]);
        Stream::factory()->create(['title' => 'Stream #2', 'scheduled_start_time' => $scheduledStartTime2]);

        // Act & Assert
        $this->get('/?timezone=Europe/Vienna')
            ->assertDontSee(today()->format('D d.m.Y'))
            ->assertSee('Today')
            ->assertDontSee(Carbon::tomorrow()->format('D d.m.Y'))
            ->assertSee('Tomorrow');
    }

    /** @test **/
    public function it_does_not_show_old_streams(): void
    {
        // Arrange
        $scheduledStartTime1 = Carbon::yesterday();
        $scheduledStartTime2 = Carbon::today();
        $scheduledStartTime3 = Carbon::now()->addDays();
        Stream::factory()->create(['title' => 'Stream #1', 'scheduled_start_time' => $scheduledStartTime1]);
        Stream::factory()->create(['title' => 'Stream #2', 'scheduled_start_time' => $scheduledStartTime2]);
        Stream::factory()->create(['title' => 'Stream #3', 'scheduled_start_time' => $scheduledStartTime3]);

        // Act & Assert
        $this->get('/?timezone=Europe/Vienna')
            ->assertSee('Stream #2')
            ->assertSee('Stream #3')
            ->assertDontSee('Stream #1');
    }
}
