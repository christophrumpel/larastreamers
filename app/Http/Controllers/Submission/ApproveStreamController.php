<?php

namespace App\Http\Controllers\Submission;

use App\Actions\Submission\ApproveStream;
use App\Models\Stream;

class ApproveStreamController
{
    public function __invoke(Stream $stream, ApproveStream $approveStream)
    {
        $approveStream->handle($stream);

        return view('pages.streamApproved');
    }
}
