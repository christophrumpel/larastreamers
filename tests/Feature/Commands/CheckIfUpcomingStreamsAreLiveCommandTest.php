<?php

use App\Console\Commands\CheckIfUpcomingStreamsAreLiveCommand;
use App\Facades\YouTube;
use App\Models\Stream;
use App\Services\YouTube\StreamData;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

it('updates upcoming streams that are soon live', function() {
    // Arrange
    Http::fake();

    // Arrange
    Stream::factory()->upcoming()->create(['scheduled_start_time' => now()->addMinutes(15)]);
    Stream::factory()->upcoming()->create(['scheduled_start_time' => now()->addMinutes(35)]);

    // Act & Expect
    $this->artisan(CheckIfUpcomingStreamsAreLiveCommand::class)
        ->expectsOutput('Fetching 1 stream(s) from API to update their status.')
        ->assertExitCode(0);
});

it('does not update finished or live streams', function() {
    // Arrange
    Http::fake();
    Stream::factory()->live()->create();
    Stream::factory()->finished()->create();

    // Act & Expect
    $this->artisan(CheckIfUpcomingStreamsAreLiveCommand::class)
        ->expectsOutput('There are no streams to update.')
        ->assertExitCode(0);
});

it('does not update unapproved streams', function() {
    // Arrange
    Http::fake();

    // Arrange
    Stream::factory()->notApproved()->create(['scheduled_start_time' => now()->addMinutes(15)]);

    // Act & Expect
    $this->artisan(CheckIfUpcomingStreamsAreLiveCommand::class)
        ->expectsOutput('There are no streams to update.')
        ->assertExitCode(0);
});

it('sets upcoming streams live and records their actual start time', function() {
    Carbon::setTestNow(now());

    // Arrange
    YouTube::partialMock()
        ->shouldReceive('videos')
        ->once()
        ->andReturn(collect([
            StreamData::fake(
                videoId: 'going-live',
                status: StreamData::STATUS_LIVE,
                plannedStart: Carbon::now()->addMinutes(10),
                actualStartTime: Carbon::now()->subMinutes(2),
                actualEndTime: null,
            ),
        ]));

    $stream = Stream::factory()->upcoming()->create([
        'youtube_id' => 'going-live',
        'scheduled_start_time' => now()->addMinutes(10),
    ]);

    // Act
    $this->artisan(CheckIfUpcomingStreamsAreLiveCommand::class)
        ->expectsOutput('1 stream(s) were updated.')
        ->assertExitCode(0);

    // Assert
    $stream->refresh();

    expect($stream->status)->toBe(StreamData::STATUS_LIVE);
    expect($stream->actual_start_time->toIso8601String())->toBe(Carbon::now()->subMinutes(2)->toIso8601String());
});

it('marks upcoming streams that YouTube no longer returns as deleted', function() {
    Carbon::setTestNow(now());

    // Arrange
    YouTube::partialMock()
        ->shouldReceive('videos')
        ->once()
        ->andReturn(collect());

    $stream = Stream::factory()->upcoming()->create([
        'youtube_id' => 'removed',
        'scheduled_start_time' => now()->addMinutes(10),
    ]);

    // Act
    $this->artisan(CheckIfUpcomingStreamsAreLiveCommand::class)
        ->expectsOutput('1 stream(s) were updated.')
        ->assertExitCode(0);

    // Assert
    $stream->refresh();

    expect($stream->status)->toBe(StreamData::STATUS_DELETED);
    expect($stream->hidden_at->toIso8601String())->toBe(Carbon::now()->toIso8601String());
});
