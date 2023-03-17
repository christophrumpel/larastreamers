<?php

namespace App\Http\Controllers;

class PagePrivacyPolicyController extends Controller
{
    public function __invoke()
    {
        return view('pages.privacy-policy');
    }
}
