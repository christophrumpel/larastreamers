<?php

namespace Tests\Feature\Actions;

use App\Models\Stream;
use App\Services\YouTube\StreamData;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class UpdateStreamActionTest extends TestCase
{
    protected StreamData $streamData;

    protected Stream $stream;

    public function setUp(): void
    {
        parent::setUp();

        $this->stream = Stream::factory()
            ->upcoming()
            ->create([
                'youtube_id' => '1234',
                'title' => 'old title',
                'description' => 'old desc',
                'thumbnail_url' => 'old thumbnail url',
                'scheduled_start_time' => Carbon::tomorrow(),
                'actual_start_time' => null,
                'actual_end_time' => null,
                'language_code' => 'en',
            ]);

        $this->streamData = new StreamData(
            videoId: '1234',
            title: 'new title',
            channelId: '5678',
            channelTitle: 'new channel title',
            description: 'new desc',
            thumbnailUrl: 'new thumbnail url',
            publishedAt: Carbon::yesterday(),
            plannedStart: Carbon::yesterday(),
            actualStartTime: Carbon::yesterday(),
            actualEndTime: Carbon::yesterday()->addHour(),
            status: StreamData::STATUS_FINISHED
        );
    }

    /** @test */
    public function it_updates_a_stream(): void
    {
        // Act
        $updatedStream = (new UpdateStreamAction)->handle($this->stream, $this->streamData);

        // Assert
        $this->assertEquals($this->stream->id, $updatedStream->id);
        $this->assertEquals($this->streamData->title, $updatedStream->title);
        $this->assertEquals($this->streamData->description, $updatedStream->description);
        $this->assertEquals($this->streamData->thumbnailUrl, $updatedStream->thumbnail_url);
        $this->assertEquals($this->streamData->plannedStart, $updatedStream->scheduled_start_time);
        $this->assertEquals($this->streamData->actualStartTime, $updatedStream->actual_start_time);
        $this->assertEquals($this->streamData->actualEndTime, $updatedStream->actual_end_time);
        $this->assertEquals($this->streamData->status, $updatedStream->status);
    }
}
