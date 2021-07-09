<?php

use App\Http\Controllers\Api\V1\Streams\IndexController;
use Illuminate\Support\Facades\Route;

Route::get('streams', IndexController::class)->name('streams');
