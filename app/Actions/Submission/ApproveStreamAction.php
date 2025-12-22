<?php

namespace App\Actions\Submission;

use App\Actions\UpdateStreamAction;
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
        // Prevent approving streams that are already approved
        if ($stream->approved_at) {
            return;
        }

        // Prevent approving streams that have been explicitly rejected
        if ($stream->rejected_at) {
            return;
        }

        $streamData = YouTube::video($stream->youtube_id);
        (new UpdateStreamAction)->handle($stream, $streamData);

        if (is_null($stream->channel_id)) {
            Artisan::call(ImportChannelsForStreamsCommand::class, ['stream' => $stream]);
        }

        $stream->update(['approved_at' => now()]);

        Mail::to($stream->submitted_by_email)->queue(new StreamApprovedMail($stream));
    }
}
