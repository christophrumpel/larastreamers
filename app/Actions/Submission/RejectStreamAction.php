<?php

namespace App\Actions\Submission;

use App\Mail\StreamRejectedMail;
use App\Models\Stream;
use Illuminate\Support\Facades\Mail;

class RejectStreamAction
{
    public function handle(Stream $stream): void
    {
        // Prevent rejecting streams that are already approved
        if ($stream->approved_at) {
            return;
        }

        // Mark stream as rejected
        $stream->update(['rejected_at' => now()]);

        Mail::to($stream->submitted_by_email)->queue(new StreamRejectedMail($stream));
    }
}
