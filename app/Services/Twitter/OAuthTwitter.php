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
        $response = $this->twitter->post('tweets', compact('text'), true);

        if ($this->twitter->getLastHttpCode() !== 200) {
            throw TwitterException::general($this->twitter->getLastHttpCode(), json_encode($response) ?? $response->title);
        }

        return (array) $response;
    }
}
