<?php

namespace Tests\Feature;

use App\Models\Stream;
use App\Services\Youtube\StreamData;
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
        Stream::factory()->create(['title' => 'Stream #1', 'scheduled_start_time' => Carbon::now()->addDays(), 'youtube_id' => '1234', 'channel_title' => 'My Channel', 'language_code' => 'en']);
        Stream::factory()->create(['title' => 'Stream #2', 'scheduled_start_time' => Carbon::now()->addDays(2), 'youtube_id' => '12345', 'language_code' => 'fr']);
        Stream::factory()->create(['title' => 'Stream #3', 'scheduled_start_time' => Carbon::now()->addDays(3), 'youtube_id' => '123456', 'language_code' => 'es']);

        // Act & Assert
        $this->get(route('home'))
            ->assertSee('Stream #1')
            ->assertSee('https://www.youtube.com/watch?v=1234')
            ->assertSee('My Channel')
            ->assertSee('Stream #2')
            ->assertSee('French')
            ->assertSee('https://www.youtube.com/watch?v=12345')
            ->assertSee('Stream #3')
            ->assertSee('Spanish')
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
        $this->get(route('home'))
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
        $this->get(route('home'))
            ->assertDontSee(today()->format('D d.m.Y'))
            ->assertSee('Today')
            ->assertDontSee(Carbon::tomorrow()->format('D d.m.Y'))
            ->assertSee('Tomorrow');
    }

    /** @test */
    public function it_does_not_show_old_streams(): void
    {
        // Arrange
        Stream::factory()->finished()->create(['title' => 'Stream finished']);
        Stream::factory()->live()->create(['title' => 'Stream live']);
        Stream::factory()->upcoming()->create(['title' => 'Stream upcoming']);

        // Act & Assert
        $this
            ->get(route('home'))
            ->assertSee('Stream live')
            ->assertSee('Stream upcoming')
                ->assertDontSee('Stream finished');
    }

    /** @test */
    public function it_does_not_show_deleted_streams(): void
    {
        // Arrange
        Stream::factory()->deleted()->create(['title' => 'Stream deleted']);
        Stream::factory()->upcoming()->create(['title' => 'Stream upcoming']);

        // Act & Assert
        $this
            ->get(route('home'))
            ->assertSee('Stream upcoming')
            ->assertDontSee('Stream deleted');
    }

    /** @test */
    public function it_marks_live_streams(): void
    {
        // Arrange
        $stream = Stream::factory()->upcoming()->create(['title' => 'Stream #1']);

        // Act & Assert
        $this->get(route('home'))
            ->assertSee('Stream #1')
            ->assertDontSee('live</span>', false);

        $stream->update(['status' => StreamData::STATUS_LIVE]);

        $this->get(route('home'))
             ->assertSee('Stream #1')
             ->assertSee('live</span>', false);
    }

    /** @test */
    public function it_shows_footer_links(): void
    {
        // Arrange
        $twitterLink = 'https://twitter.com/larastreamers';
        $githubLink = 'https://github.com/christophrumpel/larastreamers';

        // Act & Assert
        $this->get(route('home'))
            ->assertSee($twitterLink)
            ->assertSee($githubLink);
    }

    /** @test */
    public function it_adds_button_webcal_link(): void
    {
        $this->get(route('home'))
            ->assertSee('webcal://');
    }
}
