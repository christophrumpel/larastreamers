<?php

namespace App\Http\Controllers;

use App\Actions\Submission\SubmitStream;
use App\Http\Requests\SubmitStreamRequest;

class SubmitStreamController
{
    public function __invoke(SubmitStreamRequest $request, SubmitStream $submitStream)
    {
        $submitStream->handle($request->youtube_id, $request->email);

        return response()->noContent();
    }
}
