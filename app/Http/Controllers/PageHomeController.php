<?php

namespace App\Http\Controllers;

use App\Actions\PrepareStreams;
use App\Models\Stream;
use Illuminate\Contracts\View\View;

class PageHomeController extends Controller
{
    public function __invoke(PrepareStreams $prepareStreams): View
    {
        return view('pages.home', [
            'streamsByDate' => $prepareStreams->handle(Stream::approved()->upcoming()->get()),
        ]);
    }
}
