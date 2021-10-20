<?php

namespace Tests\Feature;

use App\Models\Channel;
use App\Models\Stream;
use Carbon\Carbon;
use Tests\TestCase;
use Vinkla\Hashids\Facades\Hashids;

class PageArchiveTest extends TestCase
{
    /** @test */
    public function it_shows_only_finished_streams(): void
    {
        // Arrange
        Stream::factory()->withChannel()->finished()->create(['title' => 'Finished stream']);
        Stream::factory()->withChannel()->live()->create(['title' => 'Live stream']);
        Stream::factory()->withChannel()->upcoming()->create(['title' => 'Upcoming stream']);

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
        Stream::factory()->withChannel()->finished()->create(['title' => 'Finished one day ago', 'scheduled_start_time' => Carbon::yesterday()]);
        Stream::factory()->withChannel()->finished()->create(['title' => 'Finished two days ago', 'scheduled_start_time' => Carbon::yesterday()->subDay()]);
        Stream::factory()->withChannel()->finished()->create(['title' => 'Finished three days ago', 'scheduled_start_time' => Carbon::yesterday()->subDays(2)]);

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
        Stream::factory()
            ->withChannel()
            ->deleted()
            ->create(['title' => 'Stream deleted']);

        Stream::factory()
            ->withChannel()
            ->finished()
            ->create(['title' => 'Stream finished']);

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
            ->withChannel()
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
        Stream::factory()->withChannel()->finished()->create(['title' => 'Stream One']);
        Stream::factory()->withChannel()->finished()->create(['title' => 'Stream Two']);
        Stream::factory()->withChannel()->finished()->create(['title' => 'Stream Three']);

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
        $channelShown = Channel::factory()->create(['name' => 'Channel Shown']);
        Stream::factory()
            ->finished()
            ->for($channelShown)
            ->create(['title' => 'Stream Shown #1']);

        Stream::factory()
            ->finished()
            ->for($channelShown)
            ->create(['title' => 'Stream Shown #2']);

        Stream::factory()
            ->finished()
            ->for(Channel::factory()->create(['name' => 'Channel Hidden']))
            ->create(['title' => 'Stream Hidden']);

        // Act & Assert
        $this->get(route('archive', ['search' => 'Channel Shown']))
            ->assertSee([
                'Stream Shown #1',
                'Stream Shown #2',
                'Channel Shown',
            ])
            ->assertDontSee([
                'Stream Hidden',
            ]);
    }

    /** @test */
    public function it_searches_for_streams_by_specific_streamer(): void
    {
        // Arrange
        Stream::factory()->withChannel()->finished()->create(['title' => 'Stream Shown']);
        Stream::factory()->withChannel()->finished()->create(['title' => 'Stream Hidden']);

        // Act & Assert
        $this->get(route('archive', ['streamer' => Hashids::encode(1)]))
            ->assertSee('Stream Shown')
            ->assertDontSee('Stream Hidden');
    }

    /** @test */
    public function it_searches_for_streams_by_specific_streamer_and_search_term(): void
    {
        // Arrange
        $channel = Channel::factory()->create();
        Stream::factory()->for($channel)->finished()->create(['title' => 'Stream Shown']);
        Stream::factory()->for($channel)->finished()->create(['title' => 'Stream Hidden']);

        // Act & Assert
        $this->get(route('archive', ['streamer' => '1', 'search' => 'Shown']))
            ->assertSee('Stream Shown')
            ->assertDontSee('Stream Hidden');
    }
}
