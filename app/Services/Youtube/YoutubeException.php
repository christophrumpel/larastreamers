<?php

namespace App\Services\Youtube;

class YoutubeException extends \Exception
{
    public static function general(int $status): self
    {
        return new static("Youtube API error: {$status}");
    }

    public static function unknownChannel(string $id): self
    {
        return new static("Unknown Youtube channel: {$id}");
    }

    public static function unknownVideo(string $id): self
    {
        return new static("Unknown Youtube video: {$id}");
    }
}
