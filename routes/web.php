<?php

use App\Http\Controllers\AddSingleStreamToCalendarController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\Submission\ApproveStreamController;
use App\Http\Controllers\Submission\RejectStreamController;
use App\Http\Controllers\Submission\SubmissionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::feeds('feed');

Route::view('/', 'pages.home')
    ->name('home');

Route::view('/archive', 'pages.archive')
    ->name('archive');

Route::get('/submission', SubmissionController::class)
    ->name('submission');

Route::get('/calendar.ics', CalendarController::class)
    ->name('calendar.ics');

Route::get('/stream-{stream}.ics', AddSingleStreamToCalendarController::class)
    ->name('calendar.ics.stream');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function() {
    return view('dashboard');
})->name('dashboard');

Route::middleware('signed')->group(function() {
    Route::get('submission/{stream}/approve', ApproveStreamController::class)->name('stream.approve');
    Route::get('submission/{stream}/reject', RejectStreamController::class)->name('stream.reject');
});
