<?php

namespace App\Actions\Submission;

use App\Mail\StreamRejectedMail;
use App\Models\Stream;
use Illuminate\Support\Facades\Mail;

class RejectStreamAction
{
    public function handle(Stream $stream): void
    {
        Mail::to($stream->submitted_by_email)->queue(new StreamRejectedMail($stream));
    }
}
