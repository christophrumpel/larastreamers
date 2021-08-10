<?php

namespace Tests\Feature;

use App\Models\Stream;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_provides_streams_in_rss_feed(): void
    {
        // Arrangeta
        Stream::factory()->create([
            'title' => 'Stream tomorrow',
            'channel_title' => 'Channel one',
            'description' => 'Stream description',
            'scheduled_start_time' => Carbon::tomorrow(),
        ]);
        Stream::factory()->create(['title' => 'Stream today', 'scheduled_start_time' => Carbon::today()]);
        Stream::factory()->create(['title' => 'Stream yesterday', 'scheduled_start_time' => Carbon::yesterday()]);

        // Act
        $response = $this->get('feed');

        // Assert
        $response->assertSeeInOrder([
            'Stream tomorrow',
            'Stream description',
        ]);

        $response->assertSeeInOrder([
            'Stream tomorrow',
            'Stream today',
            'Stream yesterday',
        ]);
    }
}
