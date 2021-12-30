<?php

namespace App\Services\Twitter;

use Illuminate\Support\Manager;

class TwitterManager extends Manager
{
    public function getDefaultDriver(): string
    {
        return $this->config->get('services.twitter.driver', 'oauth');
    }

    public function createOauthDriver(): OAuthTwitter
    {
        return $this->container->get(OAuthTwitter::class);
    }

    public function createFakeDriver(): TwitterFake
    {
        return new TwitterFake();
    }

    public function createNullDriver(): NullTwitter
    {
        return new NullTwitter();
    }
}
