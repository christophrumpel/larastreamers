<?php

namespace App\Actions\Submission;

use App\Console\Commands\ImportChannelsForStreamsCommand;
use App\Facades\YouTube;
use App\Mail\StreamApprovedMail;
use App\Models\Stream;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

class ApproveStreamAction
{
    public function handle(Stream $stream): void
    {
        if (! is_null($stream->approved_at)) {
            return;
        }


        $stream->update(['approved_at' => now()]);

        if (is_null($stream->channel_id)) {
            Artisan::call(ImportChannelsForStreamsCommand::class, ['stream' => $stream]);
        }

        $youTubeResponse = YouTube::video($stream->youtube_id);

        $stream->update([
            'title' => $youTubeResponse->title,
            'description' => $youTubeResponse->description,
            'thumbnail_url' => $youTubeResponse->thumbnailUrl,
            'scheduled_start_time' => $youTubeResponse->plannedStart,
            'status' => $youTubeResponse->status,
        ]);

        Mail::to($stream->submitted_by_email)->queue(new StreamApprovedMail($stream));
    }
}
