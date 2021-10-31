<?php

namespace App\Services\YouTube;

use Carbon\Carbon;
use Spatie\DataTransferObject\DataTransferObject;

class ChannelData extends DataTransferObject
{
    public string $platformId;
    public string $youTubeCustomUrl;
    public string $name;
    public string $description;
    public Carbon $onPlatformSince;
    public string $thumbnailUrl;
    public string $country;

    public function prepareForModel(): array
    {
        return [
            'platform_id' => $this->platformId,
            'youtube_custom_url' => $this->youTubeCustomUrl,
            'name' => $this->name,
            'description' => $this->description,
            'on_platform_since' => $this->onPlatformSince,
            'thumbnail_url' => $this->thumbnailUrl,
            'country' => $this->country,
        ];
    }

    public static function fake(...$args): self
    {
        if (is_array($args[0] ?? null)) {
            $args = $args[0];
        }

        return new static(
            array_merge([
                'title' => 'My Test Channel',
                'platform_id' => '1234',
                'youtube_custom_url' => 'test',
                'name' => 'My Channel Name',
                'description' => 'Some description',
                'thumbnail_url' => 'my-new-thumbnail-url',
                'on_platform_since' => Carbon::now()->subYears(2),
                'country' => 'US',
            ], $args)
        );
    }
}
