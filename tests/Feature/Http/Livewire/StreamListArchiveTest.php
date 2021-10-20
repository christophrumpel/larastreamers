<?php

namespace Tests\Feature\Http\Livewire;

use App\Http\Livewire\StreamListArchive;
use App\Models\Channel;
use App\Models\Stream;
use Livewire\Livewire;
use Tests\TestCase;
use Vinkla\Hashids\Facades\Hashids;

class StreamListArchiveTest extends TestCase
{
    /** @test */
    public function it_only_shows_streams_by_selected_streamer(): void
    {
        // Arrange
        Stream::factory()
            ->for(Channel::factory()->create(['name' => 'My Channel']))
            ->finished()
            ->create(['title' => 'Stream Seen', 'channel_id' => 1]);
        Stream::factory()
            ->for(Channel::factory()->create(['name' => 'My Channel']))
            ->finished()
            ->create(['title' => 'Stream Not Seen', 'channel_id' => 2]);

        // Act & Assert
        Livewire::test(StreamListArchive::class)
            ->set('streamer', Hashids::encode(1))
            ->assertSee('Stream Seen')
            ->assertDontSee('Stream Not Seen');
    }

    /** @test */
    public function it_shows_streamers_as_dropdown_options(): void
    {
        // Arrange
        Channel::factory()
            ->create(['name' => 'Channel A']);
        Channel::factory()
            ->create(['name' => 'Channel B']);

        // Arrange & Act & Assert
        Livewire::test(StreamListArchive::class)
            ->assertSee([
                'Channel A',
                'Channel B',
            ]);
    }

    /** @test */
    public function it_wires_properties_and_methods(): void
    {
        // Arrange & Act & Assert
        Livewire::test(StreamListArchive::class)
            ->assertPropertyWired('streamer');
    }
}
