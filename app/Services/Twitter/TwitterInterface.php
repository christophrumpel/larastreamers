<?php

namespace App\Services\Twitter;

interface TwitterInterface
{
    public function tweet(string $text): ?array;
}
