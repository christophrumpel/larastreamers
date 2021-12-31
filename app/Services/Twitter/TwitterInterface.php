<?php

namespace App\Services\Twitter;

interface TwitterInterface
{
    public function tweet(string $status): ?array;
}
