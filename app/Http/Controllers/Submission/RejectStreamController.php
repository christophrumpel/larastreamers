<?php

namespace App\Http\Controllers\Submission;

use App\Actions\Submission\RejectStreamAction;
use App\Models\Stream;
use Illuminate\Contracts\View\View;

class RejectStreamController
{
    public function __invoke(Stream $stream, RejectStreamAction $rejectStream): View
    {
        $rejectStream->handle($stream);

        return view('pages.streamRejected');
    }
}
