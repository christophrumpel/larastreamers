<?php

namespace App\Console;

use App\Console\Commands\ImportChannelStreamsCommand;
use App\Console\Commands\TweetAboutLiveStreamsCommand;
use App\Console\Commands\UpdateGivenStreams;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\UpdateArchivedStreamsCommand;
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
        UpdateGivenStreams::class,
        ImportChannelStreamsCommand::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(CleanupCommand::class)->daily()->at('01:00');
        $schedule->command(BackupCommand::class, ['--only-db', '--disable-notifications'])->daily()->at('02:00');
        $schedule->command(UpdateGivenStreams::class)->hourly();
        $schedule->command(UpdateGivenStreams::class, ['--soon-live-only'])->everyFiveMinutes();
        $schedule->command(UpdateArchivedStreamsCommand::class)->daily();
        $schedule->command(TweetAboutLiveStreamsCommand::class)->everyMinute();
        $schedule->command(ImportChannelStreamsCommand::class)->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
