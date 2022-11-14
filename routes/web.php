<?php

use App\Enums\TwitchEventType;
use App\Facades\Twitch;
use App\Http\Controllers\AddSingleStreamToCalendarController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\PageHomeController;
use App\Http\Controllers\PageStreamersController;
use App\Http\Controllers\Submission\ApproveStreamController;
use App\Http\Controllers\Submission\RejectStreamController;
use App\Models\Channel;
use App\Models\Stream;
use App\Models\TwitchChannelSubscription;
use App\Services\YouTube\StreamData;
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

Route::get('/', PageHomeController::class)
    ->name('home');

Route::view('/archive', 'pages.archive')
    ->name('archive');

Route::get('/streamers', PageStreamersController::class)
    ->name('streamers');

Route::get('/calendar.ics', CalendarController::class)
    ->name('calendar.ics');

Route::get('/stream-{stream}.ics', AddSingleStreamToCalendarController::class)
    ->name('calendar.ics.stream');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
});

Route::middleware('signed')->group(function () {
    Route::get('submission/{stream}/approve', ApproveStreamController::class)->name('stream.approve');
    Route::get('submission/{stream}/reject', RejectStreamController::class)->name('stream.reject');
});

Route::any('webhooks', function (\Illuminate\Http\Request $request) {
    ray($request);

    if ($request->header('twitch-eventsub-message-type') === 'webhook_callback_verification') {

        TwitchChannelSubscription::query()
            ->where('channel_id', Channel::where('platform_id', $request->get('subscription')['condition']['broadcaster_user_id'])->first()->id)
            ->where('subscription_event', TwitchEventType::from($request->get('subscription')['type']))
            ->firstOrFail()
            ->update(['verified' => true]);

        return response($request->get('challenge'), 200)
            ->header('Content-Type', 'text/plain');
    }

    if ($request->header('twitch-eventsub-message-type') === 'notification') {

        // check if channel given
        if (! $channel = Channel::where('platform_id', $request->get('subscription')['condition']['broadcaster_user_id'])->first()) {
            return response('');
        }

        if ($request->get('subscription')['type'] === 'stream.online') {

            // Get channel info with title of current stream
            $channelData = Twitch::channel($request->get('subscription')['condition']['broadcaster_user_id']);

            Stream::create([
                'channel_id' => $channel->id,
                'title' => $channelData->title,
                'thumbnail_url' => $channel->thumbnail_url,
                'scheduled_start_time' => now(),
                'status' => StreamData::STATUS_LIVE,
            ]);
        }
    }


})->name('webhooks');
