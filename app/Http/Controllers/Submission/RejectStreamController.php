<?php

namespace App\Http\Controllers\Submission;

use App\Actions\Submission\RejectStreamAction;
use App\Models\Stream;

class RejectStreamController
{
    public function __invoke(Stream $stream, RejectStreamAction $rejectStream)
    {
        $rejectStream->handle($stream);

        return view('pages.streamRejected');
    }
}
