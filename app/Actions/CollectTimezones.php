<?php

namespace App\Actions;

use Illuminate\Support\Facades\Cache;
use PragmaRX\Countries\Package\Countries;
use PragmaRX\Countries\Package\Services\Config;

class CollectTimezones
{
    public function handle(): array
    {
        return Cache::rememberForever('timezones', function () {
            $countries = new Countries(new Config([
                'hydrate' => [
                    'elements' => [
                        'currencies' => true,
                        'flag' => true,
                        'timezones' => true,
                    ],
                ],
            ]));

            return $countries
                ->all()
                ->map(function ($country) {
                    return $country->timezones->first()->zone_name ?? null;
                })
                ->sort()
                ->filter(fn($timezone) => !is_null($timezone))
                ->toArray();
        });
    }
}
