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

    /** @test */
    public function it_does_not_show_deleted_streams(): void
    {
        // Arrange
        Stream::factory()->deleted()->create(['title' => 'Stream deleted']);
        Stream::factory()->finished()->create(['title' => 'Stream finished']);

        // Act & Assert
        $this
            ->get(route('archive'))
            ->assertSee('Stream finished')
            ->assertDontSee('Stream deleted');
    }

    /** @test */
    public function it_searches_for_streams_on_title(): void
    {
        // Arrange
        Stream::factory()->finished()->create(['title' => 'Stream One']);
        Stream::factory()->finished()->create(['title' => 'Stream Two']);
        Stream::factory()->finished()->create(['title' => 'Stream Three']);

        // Act & Assert
        $this->get(route('archive', ['search' => 'three']))
            ->assertSee([
                'Stream Three',
            ])->assertDontSee([
                'Stream One',
                'Stream Two',
            ]);
    }

    /** @test */
    public function it_searches_for_streams_on_channel_title(): void
    {
        // Arrange
        Stream::factory()->finished()->create(['title' => 'Stream #1', 'channel_title' => 'Laravel']);
        Stream::factory()->finished()->create(['title' => 'Stream #2', 'channel_title' => 'Laravel']);
        Stream::factory()->finished()->create(['title' => 'Stream #3', 'channel_title' => 'The Streamers']);

        // Act & Assert
        $this->get(route('archive', ['search' => 'Laravel']))
            ->assertSee([
                'Stream #1',
                'Stream #2',
                'Laravel',
            ])
            ->assertDontSee([
                'Stream #3',
                'The Streamers',
            ]);
    }
}
