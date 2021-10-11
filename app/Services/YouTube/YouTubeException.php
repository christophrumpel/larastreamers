<?php

namespace App\Services\YouTube;

use Exception;

class YouTubeException extends Exception
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
