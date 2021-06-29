<?php

namespace Tests\Feature;

use App\Models\Stream;
use App\Services\Youtube\StreamData;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageArchiveTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_only_finished_streams(): void
    {
        // Arrange
        Stream::factory()->create(['title' => 'Finished stream', 'status' => StreamData::STATUS_FINISHED]);
        Stream::factory()->create(['title' => 'Live stream', 'status' => StreamData::STATUS_LIVE]);
        Stream::factory()->create(['title' => 'Upcoming stream', 'status' => StreamData::STATUS_UPCOMING]);

        // Act & Assert
        $this->get(route('archive'))
            ->assertDontSee('Live stream')
            ->assertDontSee('Upcoming stream')
            ->assertSee('Finished stream');
    }

    /** @test */
    public function it_orders_streams_from_latest_to_oldest(): void
    {
        // Arrange
        Stream::factory()->create(['title' => 'Finished one day ago', 'status' => StreamData::STATUS_FINISHED, 'scheduled_start_time' => Carbon::yesterday()]);
        Stream::factory()->create(['title' => 'Finished two days ago', 'status' => StreamData::STATUS_FINISHED, 'scheduled_start_time' => Carbon::yesterday()->subDay()]);
        Stream::factory()->create(['title' => 'Finished three days ago', 'status' => StreamData::STATUS_FINISHED, 'scheduled_start_time' => Carbon::yesterday()->subDays(2)]);

        // Act & Assert
        $this->get(route('archive'))
            ->assertSeeInOrder([
                'Finished one day ago',
                'Finished two days ago',
                'Finished three days ago',
            ]);
    }
}
