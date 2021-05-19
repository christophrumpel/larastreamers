<?php

namespace App\Http\Controllers;

use App\Actions\PrepareStreams;
use App\Models\Stream;
use Illuminate\Contracts\View\View;

class ArchiveController extends Controller
{
    public function __invoke(): View
    {
        $pastStreams = (new PrepareStreams())->handle(Stream::past()->get());

        return view('pages.archive', ['pastStreamsByDate' => $pastStreams]);
    }
}
