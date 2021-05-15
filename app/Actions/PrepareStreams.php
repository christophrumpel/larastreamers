<?php


namespace App\Actions;


use App\Models\Stream;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class PrepareStreams
{
    public function handle(Collection $streams): Collection
    {
        return $streams
            ->sortBy('scheduled_start_time')
            ->groupBy(function ($item) {
                return $item->scheduled_start_time->format('D d.m.Y');
            })
            ->mapWithKeys(function ($item, $date) {
                $dateObject = Carbon::createFromFormat('D d.m.Y', $date);

                if ($dateObject->isToday()) {
                    $date = 'Today';
                }

                if ($dateObject->isTomorrow()) {
                    $date = 'Tomorrow';
                }

                return [$date => $item];
            });
    }
}
