<?php

namespace Tests\Feature;

use App\Models\Stream;
use Carbon\Carbon;
use Tests\TestCase;

class PageArchiveTest extends TestCase
{
    /** @test */
    public function it_shows_only_finished_streams(): void
    {
        // Arrange
        Stream::factory()->finished()->create(['title' => 'Finished stream']);
        Stream::factory()->live()->create(['title' => 'Live stream']);
        Stream::factory()->upcoming()->create(['title' => 'Upcoming stream']);

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
        Stream::factory()->finished()->create(['title' => 'Finished one day ago', 'scheduled_start_time' => Carbon::yesterday()]);
        Stream::factory()->finished()->create(['title' => 'Finished two days ago', 'scheduled_start_time' => Carbon::yesterday()->subDay()]);
        Stream::factory()->finished()->create(['title' => 'Finished three days ago', 'scheduled_start_time' => Carbon::yesterday()->subDays(2)]);

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
    public function it_shows_duration_of_stream_if_given(): void
    {
        // Arrange
        Stream::factory()
            ->finished()
            ->create([
                'actual_start_time' => Carbon::yesterday(),
                'actual_end_time' => Carbon::yesterday()->addHour()->addMinutes(12),
            ]);

        // Act & Assert
        $this
            ->get(route('archive'))
            ->assertSee('1h 12m');
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

    /** @test */
    public function it_searches_for_streams_by_specific_streamer(): void
    {
    	// Arrange
        Stream::factory()->finished()->create(['channel_id' => 1, 'channel_title' => 'Laravel Stream']);
        Stream::factory()->finished()->create(['channel_id' => 2, 'channel_title' => 'Tailwind Stream']);

        // Act & Assert
        $this->get(route('archive', ['streamer' => '1']))
            ->assertSee('Laravel Stream')
            ->assertDontSee('Tailwind Stream');
    }

    /** @test */
    public function it_searches_for_streams_by_specific_streamer_and_search_term(): void
    {
        // Arrange
        Stream::factory()->finished()->create(['channel_id' => 1, 'title' => 'Laravel Stream']);
        Stream::factory()->finished()->create(['channel_id' => 1, 'title' => 'Tailwind Stream']);

        // Act & Assert
        $this->get(route('archive', ['streamer' => '1', 'search' => 'Laravel']))
            ->assertSee('Laravel Stream')
            ->assertDontSee('Tailwind Stream');
    }

}
