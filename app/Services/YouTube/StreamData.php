<?php

namespace App\Services\YouTube;

use Carbon\Carbon;
use Spatie\DataTransferObject\DataTransferObject;

class StreamData extends DataTransferObject
{
    public const STATUS_UPCOMING = 'upcoming';
    public const STATUS_LIVE = 'live';
    public const STATUS_FINISHED = 'finished';
    public const STATUS_DELETED = 'deleted';

    public string $videoId;
    public string $title;
    public string $channelId;
    public string $channelTitle;
    public string $description;
    public string $thumbnailUrl;
    public Carbon $publishedAt;
    public Carbon $plannedStart;
    public ?Carbon $actualStartTime;
    public ?Carbon $actualEndTime;
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
                'channelId' => '1234',
                'channelTitle' => 'My Channel Name',
                'description' => 'Some description',
                'thumbnailUrl' => 'my-new-thumbnail-url',
                'publishedAt' => Carbon::tomorrow(),
                'plannedStart' => Carbon::tomorrow(),
                'actualStartTime' => Carbon::tomorrow(),
                'actualEndTime' => Carbon::tomorrow()->addHour(),
                'status' => static::STATUS_UPCOMING,
            ], $args)
        );
    }
}
