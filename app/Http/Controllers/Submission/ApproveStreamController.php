<?php

namespace App\Http\Controllers\Submission;

use App\Actions\Submission\ApproveStreamAction;
use App\Models\Stream;
use Illuminate\Contracts\View\View;

class ApproveStreamController
{
    public function __invoke(Stream $stream, ApproveStreamAction $approveStream): View
    {
        $approveStream->handle($stream);

        return view('pages.streamApproved');
    }
}
