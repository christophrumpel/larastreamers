<?php

namespace App\Http\Controllers\Submission;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class SubmissionController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.submission');
    }
}
