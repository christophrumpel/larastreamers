<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Contracts\View\View;

class PageStreamersController extends Controller
{
    public function __invoke(): View
    {
        $channels = Channel::withCount('approvedFinishedStreams')
            ->withApprovedAndFinishedStreams()
            ->orderBy('approved_finished_streams_count', 'Desc')->get();

        return view('pages.streamers', ['channels' => $channels]);
    }
}
