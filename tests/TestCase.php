<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function channelResponse(): array
    {
        return [
            "kind" => "youtube#channelListResponse",
            "etag" => "OSm86OeA2FyNGiWotRZM2ECfjgI",
            "pageInfo" => [
                "totalResults" => 1,
                "resultsPerPage" => 5,
            ],
            "items" => [
                0 => [
                    "kind" => "youtube#channel",
                    "etag" => "Pzp1Mw-n3p5jKJvaUEpd8vRWcwk",
                    "id" => "UCdtd5QYBx9MUVXHm7qgEpxA",
                    "snippet" => [
                        "title" => "Christoph Rumpel",
                        "description" => "Hi, I'm Christoph Rumpel, a web developer, and teacher from Vienna. For the last six years, I have been working as a backend developer using PHP and Laravel daily. Since 2018 I'm operating on my own as a freelancer, consultant, and teacher. Next, to my first ebook about building chatbots in PHP, I'm also the author of Laravel Core Adventures; a video series that teaches how Laravel works under the hood.\n\nI love coding, teaching, surfing, bouldering, and his Nintendo Switch.\n",
                        "customUrl" => "christophrumpel",
                        "publishedAt" => "2010-01-12T19:15:29Z",
                        "thumbnails" => [
                            "default" => [
                                "url" => "https://yt3.ggpht.com/ytc/AAUvwniFZUkXnS4vDKY4lDohrpFsyu1V2AJwt4CFZGy25Q=s88-c-k-c0x00ffffff-no-rj",
                                "width" => 88,
                                "height" => 88,
                            ],
                            "medium" => [
                                "url" => "https://yt3.ggpht.com/ytc/AAUvwniFZUkXnS4vDKY4lDohrpFsyu1V2AJwt4CFZGy25Q=s240-c-k-c0x00ffffff-no-rj",
                                "width" => 240,
                                "height" => 240,
                            ],
                            "high" => [
                                "url" => "https://yt3.ggpht.com/ytc/AAUvwniFZUkXnS4vDKY4lDohrpFsyu1V2AJwt4CFZGy25Q=s800-c-k-c0x00ffffff-no-rj",
                                "width" => 800,
                                "height" => 800,
                            ],
                        ],
                        "localized" => [
                            "title" => "Christoph Rumpel",
                            "description" => "Hi, I'm Christoph Rumpel, a web developer, and teacher from Vienna. For the last six years, I have been working as a backend developer using PHP and Laravel daily. Since 2018 I'm operating on my own as a freelancer, consultant, and teacher. Next, to my first ebook about building chatbots in PHP, I'm also the author of Laravel Core Adventures; a video series that teaches how Laravel works under the hood.\n\nI love coding, teaching, surfing, bouldering, and his Nintendo Switch.\n",
                        ],
                        "country" => "AT",
                    ],
                ],
            ],
        ];
    }
}
