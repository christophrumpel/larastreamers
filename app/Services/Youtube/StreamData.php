<?php

namespace App\Services\Youtube;

use Carbon\Carbon;
use Spatie\DataTransferObject\DataTransferObject;

class StreamData extends DataTransferObject
{
    public const STATUS_UPCOMING = 'upcoming';
    public const STATUS_LIVE = 'live';
    public const STATUS_FINISHED = 'none';

    public string $videoId;
    public string $title;
    public string $channelTitle;
    public string $description;
    public string $thumbnailUrl;
    public Carbon $publishedAt;
    public Carbon $plannedStart;
    public string $status;

    public function isLive(): bool
    {
        return $this->status === self::STATUS_LIVE;
    }

    public static function fake(...$args): self
    {
        if (is_array($args[0] ?? null)) {
            $args = $args[0];
        }

        return new static(
            array_merge([
                'title' => 'My Test Stream',
                'channelTitle' => 'My Channel Name',
                'description' => 'Some description',
                'thumbnailUrl' => 'my-new-thumbnail-url',
                'publishedAt' => Carbon::tomorrow(),
                'plannedStart' => Carbon::tomorrow(),
                'status' => static::STATUS_UPCOMING,
            ], $args)
        );
    }
}
