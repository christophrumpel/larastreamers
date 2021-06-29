<?php

namespace App\Http\Controllers\Submission;

use App\Actions\Submission\SubmitStreamAction;
use App\Http\Requests\SubmitStreamRequest;

class SubmitStreamController
{
    public function __invoke(SubmitStreamRequest $request, SubmitStreamAction $submitStream)
    {
        $submitStream->handle($request->youtube_id, $request->email);

        return response()->noContent();
    }
}
