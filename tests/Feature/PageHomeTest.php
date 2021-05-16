<?php

namespace Tests\Feature;

use App\Models\Stream;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PageHomeTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function it_shows_given_streams_on_home_page(): void
    {
    	// Arrange
    	Stream::factory()->create(['title' => 'Stream #1', 'scheduled_start_time' => Carbon::now()->addDays(), 'youtube_id' => '1234', 'channel_title' => 'My Channel']);
    	Stream::factory()->create(['title' => 'Stream #2', 'scheduled_start_time' => Carbon::now()->addDays(2), 'youtube_id' => '12345']);
    	Stream::factory()->create(['title' => 'Stream #3', 'scheduled_start_time' => Carbon::now()->addDays(3), 'youtube_id' => '123456']);

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

    /** @test */
    public function it_shows_from_closest_to_farthest(): void
    {
        // Arrange
        Stream::factory()->create(['title' => 'Stream #1', 'scheduled_start_time' => Carbon::tomorrow()]);
        Stream::factory()->create(['title' => 'Stream #2', 'scheduled_start_time' => Carbon::tomorrow()->addDay()]);
        Stream::factory()->create(['title' => 'Stream #3', 'scheduled_start_time' => Carbon::tomorrow()->addDays(2)]);

        // Act & Assert
        $this->get('/')
            ->assertSeeInOrder(['Stream #1', 'Stream #2', 'Stream #3']);
    }

    /** @test */
    public function it_shows_unique_names_for_today_and_tomorrow_instead_of_whole_date(): void
    {
        $this->withoutExceptionHandling();
        // Arrange
        Stream::factory()->create(['title' => 'Stream #1', 'scheduled_start_time' => Carbon::today()->hour(2)]);
        Stream::factory()->create(['title' => 'Stream #2', 'scheduled_start_time' => Carbon::tomorrow()]);

        // Act & Assert
        $this->get('/?timezone=Europe/Vienna')
            ->assertDontSee(today()->format('D d.m.Y'))
            ->assertSee('Today')
            ->assertDontSee(Carbon::tomorrow()->format('D d.m.Y'))
            ->assertSee('Tomorrow');
    }

    /** @test */
    public function it_does_not_show_old_streams(): void
    {
        // Arrange
        Stream::factory()->create(['title' => 'Stream #1', 'scheduled_start_time' => Carbon::yesterday()->hour(8)]);
        Stream::factory()->create(['title' => 'Stream #2', 'scheduled_start_time' => Carbon::yesterday()->subDay()]);
        Stream::factory()->create(['title' => 'Stream #3', 'scheduled_start_time' => Carbon::now()->addDays()]);

        // Act & Assert
        $this->get('/')
            ->assertSee('Stream #3')
            ->assertDontSee('Stream #2')
            ->assertDontSee('Stream #1');
    }

    /** @test */
    public function it_shows_footer_links(): void
    {
        // Arrange
        $twitterLink = "https://twitter.com/larastreamers";
        $githubLink = "https://github.com/christophrumpel/larastreamers";

        // Act & Assert
        $this->get('/')
            ->assertSee($twitterLink)
            ->assertSee($githubLink);
    }
}
