<?php

use App\Console\Commands\CheckIfLiveStreamsHaveEndedCommand;
use App\Models\Stream;
use Illuminate\Support\Facades\Http;

it('updates streams that are currently live', function() {
    // Arrange
    Http::fake();

    // Arrange
    Stream::factory()->live()->create();

    // Act & Expect
    $this->artisan(CheckIfLiveStreamsHaveEndedCommand::class)
        ->expectsOutput('Fetching 1 stream(s) from API to update their status.')
        ->assertExitCode(0);
});

it('does not update finished or upcoming streams', function() {
    // Arrange
    Http::fake();

    // Arrange
    Stream::factory()->upcoming()->create();
    Stream::factory()->finished()->create();

    // Act & Expect
    $this->artisan(CheckIfLiveStreamsHaveEndedCommand::class)
        ->expectsOutput('There are no streams to update.')
        ->assertExitCode(0);
});
