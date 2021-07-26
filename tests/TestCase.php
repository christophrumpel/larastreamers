<?php

namespace Tests;

use App\Services\Twitter;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Fakes\TwitterFake;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected TwitterFake $twitterFake;

    private string $originalYoutubeApiKey;

    public function setUp(): void
    {
        parent::setUp();

        $this->originalYoutubeApiKey = config()->get('services.youtube.key', 'REAL-YOUTUBE-API-KEY') ?? '';
        config()->set('services.youtube.key', 'FAKE-YOUTUBE-KEY');
        $this->twitterFake = new TwitterFake();
        $this->app->instance(Twitter::class, $this->twitterFake);

        ray()->newScreen($this->getName());
    }

    protected function useRealYoutubeApi(): void
    {
        config()->set('services.youtube.key', $this->originalYoutubeApiKey);
    }
}
