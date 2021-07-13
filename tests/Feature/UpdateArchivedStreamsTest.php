<?php

namespace Tests\Feature;

use App\Console\Commands\UpdateArchivedStreamsCommand;
use App\Facades\Youtube;
use App\Models\Stream;
use App\Services\Youtube\StreamData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class UpdateArchivedStreamsTest extends TestCase
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
        $deletedStream = Stream::factory()->finished()->create(['youtube_id' => 'deleted']);

        // Act
        $this->artisan(UpdateArchivedStreamsCommand::class);

        // Assert
        $existingStream->refresh();

        $this->assertSame(Carbon::yesterday()->addMinutes(3)->toIso8601String(), $existingStream->actual_start_time->toIso8601String());
        $this->assertSame(Carbon::yesterday()->addMinutes(93)->toIso8601String(), $existingStream->actual_end_time->toIso8601String());
        $this->assertSame(StreamData::STATUS_FINISHED, $existingStream->status);

        $deletedStream->refresh();

        $this->assertNull($deletedStream->actual_start_time);
        $this->assertSame(StreamData::STATUS_DELETED, $deletedStream->status);
        $this->assertSame(Carbon::now()->toIso8601String(), $deletedStream->hidden_at->toIso8601String());
    }
}
