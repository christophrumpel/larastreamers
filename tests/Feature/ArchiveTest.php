<?php

namespace Tests\Feature;

use App\Models\Stream;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArchiveTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_past_streams(): void
    {
    	// Arrange
        Stream::factory()->create(['title' => 'Very old stream', 'scheduled_start_time' => Carbon::today()->subYear()]);
        Stream::factory()->create(['title' => 'Yesterdays stream', 'scheduled_start_time' => Carbon::yesterday()->hours(12)->minutes(59)]);

    	// Act & Assert
        $this->get(route('archive'))
            ->assertSee('Yesterdays stream')
            ->assertSee('Very old stream');
    }

    /** @test */
    public function it_does_not_show_upcoming_streams_including_today(): void
    {
        // Arrange
        Stream::factory()->create(['title' => 'Todays stream', 'scheduled_start_time' => Carbon::today()]);

        // Act & Assert
        $this->get(route('archive'))
            ->assertDontSee('Todays stream');
    }

}
