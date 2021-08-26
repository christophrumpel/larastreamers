<?php

namespace App\Console\Commands;

use App\Models\Stream;
use App\Services\Twitter;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class TweetWeeklyStreamsSummary extends Command
{
    protected $signature = 'command:name';

    protected $description = 'Command description';

    public function handle()
    {
        $streams = Stream::query()
            ->approved()
            ->finished()
            ->whereBetween('scheduled_start_time', [
                Carbon::today()->subDays(7)->startOfDay(),
                Carbon::yesterday()->endOfDay()
            ])->get();

        if ($streams->isEmpty()) {
            $this->info('There were no streams last week.');

            return self::SUCCESS;
        }

        app(Twitter::class)
            ->tweet("Last week we had {$streams->count()} streams.");

        return self::SUCCESS;
    }
}
