<?php

use App\Console\Commands\CheckIfLiveStreamsHaveEndedCommand;
use App\Facades\YouTube;
use App\Models\Stream;
use App\Services\YouTube\StreamData;
use Illuminate\Support\Carbon;
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

it('marks live streams as finished with their actual end time', function() {
    Carbon::setTestNow(now());

    // Arrange
    YouTube::partialMock()
        ->shouldReceive('videos')
        ->once()
        ->andReturn(collect([
            StreamData::fake(
                videoId: 'ended',
                status: StreamData::STATUS_FINISHED,
                plannedStart: Carbon::now()->subHours(2),
                actualStartTime: Carbon::now()->subHours(2)->addMinutes(3),
                actualEndTime: Carbon::now()->subMinutes(5),
            ),
        ]));

    $stream = Stream::factory()->live()->create(['youtube_id' => 'ended']);

    // Act
    $this->artisan(CheckIfLiveStreamsHaveEndedCommand::class)
        ->expectsOutput('1 stream(s) were updated.')
        ->assertExitCode(0);

    // Assert
    $stream->refresh();

    expect($stream->status)->toBe(StreamData::STATUS_FINISHED);
    expect($stream->actual_start_time->toIso8601String())->toBe(Carbon::now()->subHours(2)->addMinutes(3)->toIso8601String());
    expect($stream->actual_end_time->toIso8601String())->toBe(Carbon::now()->subMinutes(5)->toIso8601String());
});

it('marks live streams that YouTube no longer returns as deleted', function() {
    Carbon::setTestNow(now());

    // Arrange
    YouTube::partialMock()
        ->shouldReceive('videos')
        ->once()
        ->andReturn(collect());

    $stream = Stream::factory()->live()->create(['youtube_id' => 'private-now']);

    // Act
    $this->artisan(CheckIfLiveStreamsHaveEndedCommand::class)
        ->expectsOutput('1 stream(s) were updated.')
        ->assertExitCode(0);

    // Assert
    $stream->refresh();

    expect($stream->status)->toBe(StreamData::STATUS_DELETED);
    expect($stream->hidden_at->toIso8601String())->toBe(Carbon::now()->toIso8601String());
    expect(Stream::query()->live()->count())->toBe(0);
});
