<?php

namespace App\Services\Twitter;

use Exception;

class TwitterException extends Exception
{
    public static function general(int $status, string $message = ''): self
    {
        return new static("Twitter API error: $status - $message");
    }

}
