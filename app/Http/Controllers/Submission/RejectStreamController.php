<?php

namespace App\Http\Controllers\Submission;

use App\Actions\Submission\ApproveStream;
use App\Actions\Submission\RejectStream;
use App\Models\Stream;

class RejectStreamController
{
    public function __invoke(Stream $stream, RejectStream $rejectStream)
    {
        $rejectStream->handle($stream);

        return view('pages.streamRejected');
    }
}
