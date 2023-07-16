<?php

namespace App\Services\Twitter;

use Abraham\TwitterOAuth\TwitterOAuth;

class OAuthTwitter implements TwitterInterface
{
    public function __construct(
        protected TwitterOAuth $twitter,
    ) {
    }

    public function tweet(string $text): ?array
    {
        return (array) $this->twitter->post('tweets', compact('text'));
    }
}
