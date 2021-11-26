<?php

use App\Console\Commands\UpdateLiveAndFinishedStreamsCommand;
use App\Facades\YouTube;
use App\Models\Stream;
use App\Services\YouTube\StreamData;
use Illuminate\Support\Carbon;

it('updates archived streams', function() {
    Carbon::setTestNow(now());

    // Arrange
    YouTube::partialMock()
        ->shouldReceive('videos')
        ->once()
        ->andReturn(collect([
            StreamData::fake(
                videoId: 'existing',
                title: 'My New Test Stream',
                description: 'My New Description',
                channelTitle: 'My New Channel Name',
                status: StreamData::STATUS_FINISHED,
                plannedStart: Carbon::yesterday(),
                actualStartTime: Carbon::yesterday()->addMinutes(3),
                actualEndTime: Carbon::yesterday()->addMinutes(93)
            ),
        ]));

    $existingStream = Stream::factory()->finished()->create(['youtube_id' => 'existing']);

    // Act
    $this->artisan(UpdateLiveAndFinishedStreamsCommand::class)
        ->expectsOutput("Updating {$existingStream->youtube_id} ...")
        ->assertExitCode(0);

    // Assert
    $existingStream->refresh();

    expect($existingStream->actual_start_time->toIso8601String())->toBe(Carbon::yesterday()->addMinutes(3)->toIso8601String());
    expect($existingStream->actual_end_time->toIso8601String())->toBe(Carbon::yesterday()->addMinutes(93)->toIso8601String());
    expect($existingStream->status)->toBe(StreamData::STATUS_FINISHED);
});

it('marks missing streams as deleted', function() {
    Carbon::setTestNow(now());

    // Arrange
    YouTube::partialMock()
        ->shouldReceive('videos')
        ->once()
        ->andReturn(collect());

    $deletedStream = Stream::factory()->finished()->create(['youtube_id' => 'deleted']);

    // Act
    $this->artisan(UpdateLiveAndFinishedStreamsCommand::class)
        ->expectsOutput("Updating {$deletedStream->youtube_id} ...")
        ->assertExitCode(0);

    // Assert
    $deletedStream->refresh();

    expect($deletedStream->actual_start_time)->toBeNull();
    expect($deletedStream->status)->toBe(StreamData::STATUS_DELETED);
    expect($deletedStream->hidden_at->toIso8601String())->toBe(Carbon::now()->toIso8601String());
});

it('updates live streams', function() {
    Carbon::setTestNow(now());

    // Arrange
    YouTube::partialMock()
        ->shouldReceive('videos')
        ->once()
        ->andReturn(collect([
            StreamData::fake(
                videoId: 'live',
                title: 'My New Test Stream',
                description: 'My New Description',
                channelTitle: 'My New Channel Name',
                status: StreamData::STATUS_FINISHED,
                plannedStart: Carbon::yesterday(),
                actualStartTime: Carbon::yesterday()->addMinutes(3),
                actualEndTime: Carbon::yesterday()->addMinutes(93)
            ),
        ]));

    $wasLiveStream = Stream::factory()->live()->create(['youtube_id' => 'live']);

    // Act
    $this->artisan(UpdateLiveAndFinishedStreamsCommand::class)
        ->expectsOutput("Updating {$wasLiveStream->youtube_id} ...")
        ->assertExitCode(0);

    // Assert
    $wasLiveStream->refresh();

    expect($wasLiveStream->actual_start_time->toIso8601String())->toBe(Carbon::yesterday()->addMinutes(3)->toIso8601String());
    expect($wasLiveStream->actual_end_time->toIso8601String())->toBe(Carbon::yesterday()->addMinutes(93)->toIso8601String());
    expect($wasLiveStream->status)->toBe(StreamData::STATUS_FINISHED);
});

it('limits the update to the latest 50 streams', function() {
    Carbon::setTestNow(now());

    // Arrange
    YouTube::partialMock()
        ->shouldReceive('videos')
        ->once()
        ->andReturn(collect([
            StreamData::fake(
                videoId: 'stream-50',
                title: 'My New Test Stream',
                description: 'My New Description',
                channelTitle: 'My New Channel Name',
                status: StreamData::STATUS_FINISHED,
                plannedStart: Carbon::yesterday(),
                actualStartTime: Carbon::yesterday()->addMinutes(3),
                actualEndTime: Carbon::yesterday()->addMinutes(93)
            ),
        ]));

    Stream::factory(50)->finished()->create(['scheduled_start_time' => Carbon::yesterday()]);
    $stream51 = Stream::factory()->finished()->create(['youtube_id' => 'stream-50', 'scheduled_start_time' => Carbon::yesterday()->subDay()]);

    // Act
    $this->artisan(UpdateLiveAndFinishedStreamsCommand::class)
        ->doesntExpectOutput("Updating {$stream51->youtube_id} ...")
        ->assertExitCode(0);

    // Assert
    $stream51->refresh();

    expect($stream51->actual_start_time)->toBeNull();
    expect($stream51->status)->toBe(StreamData::STATUS_FINISHED);
});
