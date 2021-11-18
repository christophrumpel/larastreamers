<?php

namespace Tests\Feature\Actions;

use App\Actions\UpdateStreamAction;
use App\Models\Stream;
use App\Services\YouTube\StreamData;
use Illuminate\Support\Carbon;
use Tests\TestCase;


beforeEach(function () {
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
});

it('updates a stream', function () {
    // Act
    $updatedStream = (new UpdateStreamAction)->handle($this->stream, $this->streamData);

    // Assert
    expect($updatedStream->id)->toEqual($this->stream->id);
    expect($updatedStream->title)->toEqual($this->streamData->title);
    expect($updatedStream->description)->toEqual($this->streamData->description);
    expect($updatedStream->thumbnail_url)->toEqual($this->streamData->thumbnailUrl);
    expect($updatedStream->scheduled_start_time)->toEqual($this->streamData->plannedStart);
    expect($updatedStream->actual_start_time)->toEqual($this->streamData->actualStartTime);
    expect($updatedStream->actual_end_time)->toEqual($this->streamData->actualEndTime);
    expect($updatedStream->status)->toEqual($this->streamData->status);
});
