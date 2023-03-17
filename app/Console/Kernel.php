<?php

namespace App\Console;

use App\Console\Commands\CheckIfLiveStreamsHaveEndedCommand;
use App\Console\Commands\CheckIfUpcomingStreamsAreLiveCommand;
use App\Console\Commands\ImportChannelsForStreamsCommand;
use App\Console\Commands\ImportChannelStreamsCommand;
use App\Console\Commands\TweetAboutLiveStreamsCommand;
use App\Console\Commands\TweetAboutUpcomingStreamsCommand;
use App\Console\Commands\TweetAboutWeeklySummaryCommand;
use App\Console\Commands\UpdateChannelsCommand;
use App\Console\Commands\UpdateLiveAndFinishedStreamsCommand;
use App\Console\Commands\UpdateUpcomingStreamsCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Spatie\Backup\Commands\BackupCommand;
use Spatie\Backup\Commands\CleanupCommand;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        TweetAboutLiveStreamsCommand::class,
        UpdateUpcomingStreamsCommand::class,
        ImportChannelStreamsCommand::class,
        TweetAboutWeeklySummaryCommand::class,
        ImportChannelsForStreamsCommand::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(CleanupCommand::class)->daily()->at('01:00');
        $schedule->command(BackupCommand::class, ['--only-db', '--disable-notifications'])->daily()->at('02:00');
        $schedule->command(UpdateUpcomingStreamsCommand::class)->hourly();
//        $schedule->command(CheckIfUpcomingStreamsAreLiveCommand::class)->everyFiveMinutes();
//        $schedule->command(CheckIfLiveStreamsHaveEndedCommand::class)->everyTenMinutes();
        $schedule->command(ImportChannelStreamsCommand::class)->hourly();
        $schedule->command(UpdateLiveAndFinishedStreamsCommand::class)->daily();
        $schedule->command(UpdateChannelsCommand::class)->weeklyOn(1, '8:00');
        $schedule->command(TweetAboutLiveStreamsCommand::class)->everyMinute();
        $schedule->command(TweetAboutUpcomingStreamsCommand::class)->everyMinute();
        $schedule->command(TweetAboutWeeklySummaryCommand::class)->weeklyOn(1, '8:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
