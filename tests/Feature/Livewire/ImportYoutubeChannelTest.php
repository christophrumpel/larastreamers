<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\ImportYoutubeChannel;
use App\Jobs\ImportYoutubeChannelStreamsJob;
use App\Models\Channel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImportYoutubeChannelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_adds_channel_to_database(): void
    {
        // Assert
        $this->assertDatabaseCount((new Channel())->getTable(), 0);

        // Arrange & Act
        Http::fake(fn() => Http::response($this->channelResponse()));

        Livewire::test(ImportYoutubeChannel::class)
            ->set('youtubeChannelId', 'UCdtd5QYBx9MUVXHm7qgEpxA')
            ->call('importChannel');

    	// Assert
        $this->assertDatabaseCount((new Channel())->getTable(), 1);
        $this->assertDatabaseHas((new Channel())->getTable(), [
           'platform' => 'youtube',
           'platform_id' => 'UCdtd5QYBx9MUVXHm7qgEpxA'
        ]);
    }

    /** @test */
    public function it_dispatches_job_to_import_upcoming_streams(): void
    {
        // Arrange & Act
        Queue::fake();
        Http::fake(fn() => Http::response($this->channelResponse()));

        Livewire::test(ImportYoutubeChannel::class)
            ->set('youtubeChannelId', 'UCdtd5QYBx9MUVXHm7qgEpxA')
            ->call('importChannel');

        // Assert
       Queue::assertPushed(ImportYoutubeChannelStreamsJob::class);
    }


    /** @test */
    public function it_wires_properties_and_methods(): void
    {
    	// Arrange & Act & Assert
        Livewire::test(ImportYoutubeChannel::class)
            ->assertPropertyWired('youtubeChannelId')
            ->assertMethodWiredToForm('importChannel');

    }
}
