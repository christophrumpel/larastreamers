<?php

namespace App\Services\Youtube;

use Exception;

class YoutubeException extends Exception
{
    public static function general(int $status): self
    {
        return new static("YouTube API error: {$status}");
    }

    public static function unknownChannel(string $id): self
    {
        return new static("Unknown YouTube channel: {$id}");
    }

    public static function unknownVideo(string $id): self
    {
        return new static("Unknown YouTube video: {$id}");
    }
}
