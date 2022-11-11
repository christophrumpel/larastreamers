<?php

namespace Tests;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, LazilyRefreshDatabase;

    private string $originalYoutubeApiKey;

    protected function setUp(): void
    {
        parent::setUp();

        $this->originalYoutubeApiKey = config()->get('services.youtube.key', 'REAL-YOUTUBE-API-KEY') ?? '';
        config()->set('services.youtube.key', 'FAKE-YOUTUBE-KEY');
        Http::preventStrayRequests();
    }

    protected function useRealYoutubeApi(): void
    {
        config()->set('services.youtube.key', $this->originalYoutubeApiKey);
    }
}
