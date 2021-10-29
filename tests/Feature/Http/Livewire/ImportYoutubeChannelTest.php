<?php

namespace Tests\Feature\Http\Livewire;

use App\Http\Livewire\ImportYouTubeChannel;
use App\Jobs\ImportYoutubeChannelStreamsJob;
use App\Models\Channel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\Fakes\YouTubeResponses;
use Tests\TestCase;

class ImportYoutubeChannelTest extends TestCase
{
    use YouTubeResponses;

    /** @test */
    public function it_adds_channel_to_database(): void
    {
        // Arrange
        Queue::fake();

        // Assert
        $this->assertDatabaseCount(Channel::class, 0);

        // Arrange & Act
        Http::fake(fn() => Http::response($this->channelResponse()));

        Livewire::test(ImportYouTubeChannel::class)
            ->set('youTubeChannelId', 'UCdtd5QYBx9MUVXHm7qgEpxA')
            ->call('importChannel');

        // Assert
        $this->assertDatabaseCount(Channel::class, 1);
        $this->assertDatabaseHas(Channel::class, [
            'platform' => 'youtube',
            'platform_id' => 'UCdtd5QYBx9MUVXHm7qgEpxA',
        ]);
    }

    /** @test */
    public function it_dispatches_job_to_import_upcoming_streams(): void
    {
        // Arrange
        Queue::fake();
        Http::fake(fn() => Http::response($this->channelResponse()));

        // Act
        Livewire::test(ImportYouTubeChannel::class)
            ->set('youTubeChannelId', 'UCdtd5QYBx9MUVXHm7qgEpxA')
            ->call('importChannel');

        // Assert
        Queue::assertPushed(ImportYoutubeChannelStreamsJob::class);
    }

    /** @test */
    public function it_shows_success_message(): void
    {
        // Arrange
        Queue::fake();
        Http::fake(fn() => Http::response($this->channelResponse()));

        // Act & Assert
        Livewire::test(ImportYouTubeChannel::class)
            ->set('youTubeChannelId', 'UCdtd5QYBx9MUVXHm7qgEpxA')
            ->call('importChannel')
            ->assertSee('Channel "UCdtd5QYBx9MUVXHm7qgEpxA" was added successfully.');
    }

    /** @test */
    public function it_shows_youtube_client_error_message(): void
    {
        // Arrange
        Http::fake(fn() => Http::response([], 500));

        // Arrange & Act & Assert
        Livewire::test(ImportYouTubeChannel::class)
            ->set('youTubeChannelId', 'UCdtd5QYBx9MUVXHm7qgEpxA')
            ->call('importChannel')
            ->assertSee('YouTube API error: 500');
    }

    /** @test */
    public function it_clears_form_after_successful_import(): void
    {
        // Arrange
        Queue::fake();
        Http::fake(fn() => Http::response($this->channelResponse()));

        // Act & Assert
        Livewire::test(ImportYouTubeChannel::class)
            ->set('youTubeChannelId', 'UCdtd5QYBx9MUVXHm7qgEpxA')
            ->call('importChannel')
            ->assertSet('youTubeChannelId', '');
    }

    /** @test */
    public function it_wires_properties_and_methods(): void
    {
        // Arrange & Act & Assert
        Livewire::test(ImportYouTubeChannel::class)
            ->assertPropertyWired('youTubeChannelId')
            ->assertMethodWiredToForm('importChannel');
    }
}
