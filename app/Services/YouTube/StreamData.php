<?php

namespace App\Services\YouTube;

use Carbon\Carbon;

class StreamData
{
    public const STATUS_UPCOMING = 'upcoming';

    public const STATUS_LIVE = 'live';

    public const STATUS_FINISHED = 'finished';

    public const STATUS_DELETED = 'deleted';

    public function __construct(
        public readonly string $videoId,
        public readonly string $title,
        public readonly string $channelId,
        public readonly string $channelTitle,
        public readonly string $description,
        public readonly string $thumbnailUrl,
        public readonly Carbon $publishedAt,
        public readonly Carbon $plannedStart,
        public readonly ?Carbon $actualStartTime,
        public readonly ?Carbon $actualEndTime,
        public readonly string $status,
    ) {}

    public function isLive(): bool
    {
        return $this->status === self::STATUS_LIVE;
    }

    public static function fake(
        string $videoId = 'fake-video-id',
        string $title = 'My Test Stream',
        string $channelId = '1234',
        string $channelTitle = 'My Channel Name',
        string $description = 'Some description',
        string $thumbnailUrl = 'my-new-thumbnail-url',
        ?Carbon $publishedAt = null,
        ?Carbon $plannedStart = null,
        ?Carbon $actualStartTime = null,
        ?Carbon $actualEndTime = null,
        string $status = self::STATUS_UPCOMING,
    ): self {
        return new self(
            videoId: $videoId,
            title: $title,
            channelId: $channelId,
            channelTitle: $channelTitle,
            description: $description,
            thumbnailUrl: $thumbnailUrl,
            publishedAt: $publishedAt ?? Carbon::tomorrow(),
            plannedStart: $plannedStart ?? Carbon::tomorrow(),
            actualStartTime: $actualStartTime ?? Carbon::tomorrow(),
            actualEndTime: $actualEndTime ?? Carbon::tomorrow()->addHour(),
            status: $status,
        );
    }
}
