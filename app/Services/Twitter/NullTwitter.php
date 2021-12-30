<?php

namespace App\Services\Twitter;

class NullTwitter implements TwitterInterface
{
    public function tweet(string $status): ?array
    {
        return null;
    }
}
