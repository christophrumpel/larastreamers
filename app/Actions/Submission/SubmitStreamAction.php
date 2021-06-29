<?php

namespace App\Actions\Submission;

use App\Actions\ImportVideoAction;
use App\Mail\StreamSubmittedMail;
use Illuminate\Support\Facades\Mail;

class SubmitStreamAction
{
    public function handle(string $youTubeId, string $languageCode, string $submittedByEmail)
    {
        $stream = app(ImportVideoAction::class)->handle(
            $youTubeId,
            $languageCode,
            approved: false,
            submittedByEmail: $submittedByEmail,
        );

        Mail::to('christoph@christoph-rumpel.com')->queue(new StreamSubmittedMail($stream));

    }
}
