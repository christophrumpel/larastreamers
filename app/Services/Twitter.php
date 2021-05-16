<?php

namespace App\Services;

use Abraham\TwitterOAuth\TwitterOAuth;

class Twitter
{
    protected TwitterOAuth $twitter;

    public function __construct(TwitterOAuth $twitter)
    {
        $this->twitter = $twitter;
    }

    public function tweet(string $status)
    {
        /*
        if (! app()->environment('production')) {
            return;
        }
        */

        return (array) $this->twitter->post('statuses/update', compact('status'));
    }
}
