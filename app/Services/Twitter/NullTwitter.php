<?php

namespace App\Services\Twitter;

class NullTwitter implements TwitterInterface
{
    public function tweet(string $text): ?array
    {
        return null;
    }
}
