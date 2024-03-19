<?php

use Illuminate\Support\Facades\Schedule;
use Spatie\Backup\Commands\CleanupCommand;
use Spatie\Backup\Commands\BackupCommand;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\UpdateUpcomingStreamsCommand;
use App\Console\Commands\UpdateLiveAndFinishedStreamsCommand;
use App\Console\Commands\UpdateChannelsCommand;
use App\Console\Commands\TweetAboutWeeklySummaryCommand;
use App\Console\Commands\TweetAboutUpcomingStreamsCommand;
use App\Console\Commands\TweetAboutLiveStreamsCommand;
use App\Console\Commands\ImportChannelStreamsCommand;
use App\Console\Commands\ImportChannelsForStreamsCommand;
use App\Console\Commands\CheckIfUpcomingStreamsAreLiveCommand;
use App\Console\Commands\CheckIfLiveStreamsHaveEndedCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Schedule::command(CleanupCommand::class)->daily()->at('01:00');
Schedule::command(BackupCommand::class, ['--only-db', '--disable-notifications'])->daily()->at('02:00');
Schedule::command(UpdateUpcomingStreamsCommand::class)->hourly();
Schedule::command(CheckIfUpcomingStreamsAreLiveCommand::class)->everyFiveMinutes();
Schedule::command(CheckIfLiveStreamsHaveEndedCommand::class)->everyTenMinutes();
Schedule::command(ImportChannelStreamsCommand::class)->hourly();
Schedule::command(UpdateLiveAndFinishedStreamsCommand::class)->daily();
Schedule::command(UpdateChannelsCommand::class)->weeklyOn(1, '8:00');
Schedule::command(TweetAboutLiveStreamsCommand::class)->everyMinute();
Schedule::command(TweetAboutUpcomingStreamsCommand::class)->everyMinute();
Schedule::command(TweetAboutWeeklySummaryCommand::class)->weeklyOn(1, '8:00');
