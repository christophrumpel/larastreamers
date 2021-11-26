<?php

use App\Console\Commands\ImportChannelStreamsCommand;
use App\Jobs\ImportYoutubeChannelStreamsJob;
use App\Models\Channel;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;


it('runs import youtube channel streams jobs', function () {
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
});
