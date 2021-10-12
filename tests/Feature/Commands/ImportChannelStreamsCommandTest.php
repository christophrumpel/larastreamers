<?php

namespace Tests\Feature\Commands;

use App\Console\Commands\ImportChannelStreamsCommand;
use App\Jobs\ImportYoutubeChannelStreamsJob;
use App\Models\Channel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ImportChannelStreamsCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_runs_import_youtube_channel_streams_jobs(): void
    {
        // Arrange
        Queue::fake();
        Channel::factory()
            ->autoImportEnabled()
            ->count(2)
            ->create();

        // Act
        $this->artisan(ImportChannelStreamsCommand::class);

        // Assert
        Queue::assertPushed(ImportYoutubeChannelStreamsJob::class, 2);
    }
}
