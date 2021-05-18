<?php

namespace App\Console;

use App\Console\Commands\TweetAboutLiveStreams;
use App\Console\Commands\UpdateGivenStreams;
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
        TweetAboutLiveStreams::class,
        UpdateGivenStreams::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(CleanupCommand::class)->daily()->at('01:00');
        $schedule->command(BackupCommand::class, ['--only-db', '--disable-notifications'])->daily()->at('02:00');
        $schedule->command(UpdateGivenStreams::class)->hourly();
        $schedule->command(UpdateGivenStreams::class, ['--frequent'])->everyFiveMinutes();
        $schedule->command(TweetAboutLiveStreams::class)->everyMinute();
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
