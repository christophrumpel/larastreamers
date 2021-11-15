<?php

namespace App\Actions;

use App\Models\Stream;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class SortStreamsByDateAction
{
    public function handle(Collection $streams): Collection
    {
        return $streams
            ->groupBy(static fn(Stream $item): string => $item->scheduled_start_time->format('D d.m.Y'))
            ->mapWithKeys(static function(Collection $item, string $date): array {
                /** @var \Carbon\Carbon $dateObject */
                $dateObject = Carbon::createFromFormat('D d.m.Y', $date);

                $date = $dateObject->format('D, M jS Y');

                if ($dateObject->isYesterday()) {
                    $date = 'Yesterday';
                }

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
