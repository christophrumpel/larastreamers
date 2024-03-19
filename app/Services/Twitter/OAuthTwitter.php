<?php

namespace App\Services\Twitter;

use Abraham\TwitterOAuth\TwitterOAuth;

class OAuthTwitter implements TwitterInterface
{
    public function __construct(
        protected TwitterOAuth $twitter,
    )
    {
    }

    public function tweet(string $text): ?array
    {
        $response = (array)$this->twitter->post('tweets', compact('text'), true);

        if ($this->twitter->getLastHttpCode() > 201) {

            throw TwitterException::general($this->twitter->getLastHttpCode(), $response['title']);
        }

        return $response;
    }
}
