<?php

namespace Tests\Feature\Jobs;

use App\Jobs\ImportYoutubeChannelStreamsJob;
use App\Models\Channel;
use App\Models\Stream;
use Illuminate\Support\Facades\Http;
use Tests\Fakes\YouTubeResponses;
use Tests\TestCase;

class ImportYoutubeChannelStreamsJobTest extends TestCase
{
    use YouTubeResponses;

    /** @test */
    public function it_imports_youtube_channel_streams(): void
    {
        Http::fake([
            '*search*' => Http::response($this->upcomingStreamsResponse()),
            '*video*' => Http::response($this->videoResponse()),
        ]);

        // Assert
        $this->assertDatabaseCount(Stream::class, 0);

        // Act
        (new ImportYoutubeChannelStreamsJob('UCdtd5QYBx9MUVXHm7qgEpxA', 'en'))->handle();

        // Assert
        $this->assertDatabaseCount(Stream::class, 3);
        $this->assertDatabaseHas(Stream::class, [
            'youtube_id' => 'gzqJZQyfkaI',
            'language_code' => 'en',
        ]);

        $stream = Stream::first();
        $this->assertNotNull($stream->approved_at);
    }

    /** @test */
    public function it_sets_channel_id_to_new_streams(): void
    {
        // Arrange
        Http::fake([
            '*search*' => Http::response($this->upcomingStreamsResponse()),
            '*video*' => Http::response($this->videoResponse()),
        ]);

        $channel = Channel::factory()->create(['platform_id' => 'UCNlUCA4VORBx8X-h-rXvXEg']);

        // Assert
        $this->assertDatabaseCount(Stream::class, 0);

        // Act
        (new ImportYoutubeChannelStreamsJob('UCNlUCA4VORBx8X-h-rXvXEg', 'en'))->handle();

        // Assert
        $this->assertDatabaseCount(Stream::class, 3);
        $this->assertDatabaseHas(Stream::class, [
            'channel_id' => $channel->id,
        ]);

        $stream = Stream::first();
        $this->assertNotNull($stream->approved_at);
    }

    /** @test */
    public function it_updates_already_given_streams(): void
    {
        // Arrange
        Http::fake([
            '*search*' => Http::response($this->upcomingStreamsResponse()),
            '*video*' => Http::response($this->videoResponse()),
        ]);
        Stream::factory()->create(['youtube_id' => 'gzqJZQyfkaI']);

        // Act
        (new ImportYoutubeChannelStreamsJob('UCdtd5QYBx9MUVXHm7qgEpxA', 'en'))->handle();

        // Assert
        $this->assertDatabaseCount(Stream::class, 3);
    }
}
