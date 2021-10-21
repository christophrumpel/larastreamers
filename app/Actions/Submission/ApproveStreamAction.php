<?php

namespace App\Actions\Submission;

use App\Console\Commands\ImportChannelsForStreamsCommand;
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

        $stream->approved_at = now();
        $stream->save();

        Artisan::call(ImportChannelsForStreamsCommand::class, ['stream' => $stream]);

        Mail::to($stream->submitted_by_email)->queue(new StreamApprovedMail($stream));
    }
}
