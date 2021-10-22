<?php

namespace Tests\Feature\Actions;

use App\Models\Stream;
use App\Services\YouTube\StreamData;

class UpdateStreamAction
{
    public function handle(Stream $stream, StreamData $streamData): Stream
    {
        return tap($stream, function() use ($stream, $streamData) {
            $stream->update([
                'title' => $streamData->title,
                'description' => $streamData->description,
                'thumbnail_url' => $streamData->thumbnailUrl,
                'scheduled_start_time' => $streamData->plannedStart,
                'actual_start_time' => $streamData->actualStartTime,
                'actual_end_time' => $streamData->actualEndTime,
                'status' => $streamData->status,
            ]);
        });
    }
}
