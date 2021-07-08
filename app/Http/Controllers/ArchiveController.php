<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class ArchiveController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.archive');
    }
}
