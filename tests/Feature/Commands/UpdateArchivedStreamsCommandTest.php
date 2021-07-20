<?php

namespace Tests\Feature\Commands;

use App\Console\Commands\UpdateArchivedStreamsCommand;
use App\Facades\Youtube;
use App\Models\Stream;
use App\Services\Youtube\StreamData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class UpdateArchivedStreamsCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_updates_all_archived_streams(): void
    {
        Carbon::setTestNow(now());

        // Arrange
        Youtube::partialMock()
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
        $this->artisan(UpdateArchivedStreamsCommand::class)
            ->expectsOutput("Updating {$existingStream->youtube_id} ...")
            ->assertExitCode(0);

        // Assert
        $existingStream->refresh();

        $this->assertSame(Carbon::yesterday()->addMinutes(3)->toIso8601String(), $existingStream->actual_start_time->toIso8601String());
        $this->assertSame(Carbon::yesterday()->addMinutes(93)->toIso8601String(), $existingStream->actual_end_time->toIso8601String());
        $this->assertSame(StreamData::STATUS_FINISHED, $existingStream->status);
    }

    /** @test */
    public function it_marks_missing_streams_as_deleted(): void
    {
        Carbon::setTestNow(now());

        // Arrange
        Youtube::partialMock()
            ->shouldReceive('videos')
            ->once()
            ->andReturn(collect());

        $deletedStream = Stream::factory()->finished()->create(['youtube_id' => 'deleted']);

        // Act
        $this->artisan(UpdateArchivedStreamsCommand::class)
            ->expectsOutput("Updating {$deletedStream->youtube_id} ...")
            ->assertExitCode(0);

        // Assert
        $deletedStream->refresh();

        $this->assertNull($deletedStream->actual_start_time);
        $this->assertSame(StreamData::STATUS_DELETED, $deletedStream->status);
        $this->assertSame(Carbon::now()->toIso8601String(), $deletedStream->hidden_at->toIso8601String());
    }

    /** @test */
    public function it_updates_live_streams(): void
    {
        Carbon::setTestNow(now());

        // Arrange
        Youtube::partialMock()
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
        $this->artisan(UpdateArchivedStreamsCommand::class)
            ->expectsOutput("Updating {$wasLiveStream->youtube_id} ...")
            ->assertExitCode(0);

        // Assert
        $wasLiveStream->refresh();

        $this->assertSame(Carbon::yesterday()->addMinutes(3)->toIso8601String(), $wasLiveStream->actual_start_time->toIso8601String());
        $this->assertSame(Carbon::yesterday()->addMinutes(93)->toIso8601String(), $wasLiveStream->actual_end_time->toIso8601String());
        $this->assertSame(StreamData::STATUS_FINISHED, $wasLiveStream->status);
    }

    /** @test */
    public function it_limits_the_update_to_the_latest_50_streams(): void
    {
        Carbon::setTestNow(now());

        // Arrange
        Youtube::partialMock()
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
        $this->artisan(UpdateArchivedStreamsCommand::class)
            ->doesntExpectOutput("Updating {$stream51->youtube_id} ...")
            ->assertExitCode(0);

        // Assert
        $stream51->refresh();

        $this->assertNull($stream51->actual_start_time);
        $this->assertSame(StreamData::STATUS_FINISHED, $stream51->status);
    }
}
