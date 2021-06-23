<?php

namespace App\Actions\Submission;

use App\Actions\ImportVideo;
use App\Mail\StreamSubmittedMail;
use Illuminate\Support\Facades\Mail;

class SubmitStream
{
    public function handle(string $youTubeId, string $submittedByEmail)
    {
        $stream = (new ImportVideo())->handle(
            $youTubeId,
            approved: false,
            submittedByEmail: $submittedByEmail,
        );

        Mail::to('christoph@christoph-rumpel.com')->queue(new StreamSubmittedMail($stream));

    }
}
