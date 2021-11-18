<?php

use App\Console\Commands\CheckIfUpcomingStreamsAreLiveCommand;
use App\Models\Stream;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;


it('updates upcoming streams that are soon live', function () {
    // Arrange
    Http::fake();

    // Arrange
    Stream::factory()->upcoming()->create(['scheduled_start_time' => now()->addMinutes(15)]);
    Stream::factory()->upcoming()->create(['scheduled_start_time' => now()->addMinutes(20)]);

    // Act & Expect
    $this->artisan(CheckIfUpcomingStreamsAreLiveCommand::class)
        ->expectsOutput('Fetching 1 stream(s) from API to update their status.')
        ->assertExitCode(0);
});

it('does not update finished or live streams', function () {
    // Arrange
    Http::fake();
    Stream::factory()->live()->create();
    Stream::factory()->finished()->create();

    // Act & Expect
    $this->artisan(CheckIfUpcomingStreamsAreLiveCommand::class)
        ->expectsOutput('There are no streams to update.')
        ->assertExitCode(0);
});

it('does not update unapproved streams', function () {
    // Arrange
    Http::fake();

    // Arrange
    Stream::factory()->notApproved()->create(['scheduled_start_time' => now()->addMinutes(15)]);

    // Act & Expect
    $this->artisan(CheckIfUpcomingStreamsAreLiveCommand::class)
        ->expectsOutput('There are no streams to update.')
        ->assertExitCode(0);
});
