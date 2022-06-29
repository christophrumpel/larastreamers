<?php

use App\Actions\UpdateStreamAction;
use App\Models\Stream;
use App\Services\YouTube\StreamData;
use Illuminate\Support\Carbon;

beforeEach(function () {
    $this->stream = Stream::factory()
        ->upcoming()
        ->create();

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
});

it('updates a stream', function () {
    // Act
    $updatedStream = (new UpdateStreamAction())->handle($this->stream, $this->streamData);

    // Assert
    expect($updatedStream)
        ->id->toBe($this->stream->id)
        ->title->toBe($this->streamData->title)
        ->description->toBe($this->streamData->description)
        ->thumbnail_url->toBe($this->streamData->thumbnailUrl)
        ->scheduled_start_time->toEqual($this->streamData->plannedStart)
        ->actual_start_time->toEqual($this->streamData->actualStartTime)
        ->actual_end_time->toEqual($this->streamData->actualEndTime)
        ->status->toBe($this->streamData->status);
});
