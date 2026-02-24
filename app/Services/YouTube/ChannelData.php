<?php

namespace App\Services\YouTube;

use Carbon\Carbon;

class ChannelData
{
    public function __construct(
        public readonly string $platformId,
        public readonly string $youTubeCustomUrl,
        public readonly string $name,
        public readonly string $description,
        public readonly Carbon $onPlatformSince,
        public readonly string $thumbnailUrl,
        public readonly string $country,
    ) {}

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

    public static function fake(
        string $platformId = '1234',
        string $youTubeCustomUrl = 'test',
        string $name = 'My Channel Name',
        string $description = 'Some description',
        ?Carbon $onPlatformSince = null,
        string $thumbnailUrl = 'my-new-thumbnail-url',
        string $country = 'US',
    ): self {
        return new self(
            platformId: $platformId,
            youTubeCustomUrl: $youTubeCustomUrl,
            name: $name,
            description: $description,
            onPlatformSince: $onPlatformSince ?? Carbon::now()->subYears(2),
            thumbnailUrl: $thumbnailUrl,
            country: $country,
        );
    }
}
