<?php

use App\Http\Controllers\Api\V1\Streams\IndexController;

Route::prefix('v1')->group(function () {
    Route::get('streams', IndexController::class)->name('streams');
});
