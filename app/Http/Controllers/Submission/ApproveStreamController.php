<?php

namespace App\Http\Controllers\Submission;

use App\Actions\Submission\ApproveStreamAction;
use App\Models\Stream;

class ApproveStreamController
{
    public function __invoke(Stream $stream, ApproveStreamAction $approveStream)
    {
        $approveStream->handle($stream);

        return view('pages.streamApproved');
    }
}
