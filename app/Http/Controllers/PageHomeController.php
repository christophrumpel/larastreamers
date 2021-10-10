<?php

namespace App\Http\Controllers;

use App\Models\Stream;
use Illuminate\Contracts\View\View;

class PageHomeController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.home', [
            'upcomingStream' => Stream::getNextUpcomingOrLive()
        ]);
    }
}
