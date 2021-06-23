<?php

namespace App\Actions\Submission;

use App\Mail\StreamApprovedMail;
use App\Models\Stream;
use Illuminate\Support\Facades\Mail;

class ApproveStream
{
    public function handle(Stream $stream)
    {
        if (! is_null($stream->approved_at)) {
            return;
        }

        $stream->approved_at = now();

        $stream->save();

        Mail::to($stream->submitted_by_email)->queue(new StreamApprovedMail($stream));
    }
}
