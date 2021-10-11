<?php

namespace App\Services\YouTube;

use Carbon\Carbon;
use Spatie\DataTransferObject\DataTransferObject;

class ChannelData extends DataTransferObject
{
    public string $platformId;
    public string $slug;
    public string $name;
    public string $description;
    public Carbon $onPlatformSince;
    public string $thumbnailUrl;
    public string $country;

    public function prepareForModel(): array
    {
        return [
            'platform_id' => $this->platformId,
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => $this->description,
            'on_platform_since' => $this->onPlatformSince,
            'thumbnail_url' => $this->thumbnailUrl,
            'country' => $this->country,
        ];
    }
}
