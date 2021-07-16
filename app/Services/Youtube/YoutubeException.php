<?php

namespace App\Services\Youtube;

use Exception;

class YoutubeException extends Exception
{
    public static function general(int $status, string $message = ''): self
    {
        return new static("YouTube API error: $status - $message");
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
