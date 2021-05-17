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

    protected function upcomingStreamsResponse(): array
    {
        return [
            'kind' => 'youtube#searchListResponse',
            'etag' => 'o19miOtTLMOArPdPJ6v2nx7bmhQ',
            'regionCode' => 'AT',
            'pageInfo' => [
                'totalResults' => 3,
                'resultsPerPage' => 3,
            ],
            'items' => [
                0 => [
                    'kind' => 'youtube#searchResult',
                    'etag' => 'YCdfB8NLB-N6lejOhTECGF__T4M',
                    'id' => [
                        'kind' => 'youtube#video',
                        'videoId' => 'gzqJZQyfkaI',
                    ],
                ],
                1 => [
                    'kind' => 'youtube#searchResult',
                    'etag' => 'tXtZzozX0f6leA6iugaqs5w0ODM',
                    'id' => [
                        'kind' => 'youtube#video',
                        'videoId' => 'bcnR4NYOw2o',
                    ],
                ],
                2 => [
                    'kind' => 'youtube#searchResult',
                    'etag' => '8QenCk_BgoFDftPKoJHTbeQ-oAs',
                    'id' => [
                        'kind' => 'youtube#video',
                        'videoId' => 'L3O1BbybSgw',
                    ],
                ],
            ],
        ];
    }

    protected function videoResponse(): array
    {
        return [
            'kind' => 'youtube#videoListResponse',
            'etag' => 'X07FHAkzBLMnZMayS01HYFspAgI',
            'items' => [
                0 => [
                    'kind' => 'youtube#video',
                    'etag' => 'Nt1LmI_bN5gNuQnEKD-oOzityFk',
                    'id' => 'gzqJZQyfkaI',
                    'snippet' => [
                        'publishedAt' => '2021-05-15T12:51:18Z',
                        'channelId' => 'UCNlUCA4VORBx8X-h-rXvXEg',
                        'title' => 'Live coding new features for larastreamers.com',
                        'description' => "Christoph Rumpel created a nice new project: https://larastreamers.com/\nIn this stream, I'm going to add some features to Christoph's app.",
                        'thumbnails' => [
                            'default' => [
                                'url' => 'https://i.ytimg.com/vi/gzqJZQyfkaI/default.jpg',
                                'width' => 120,
                                'height' => 90,
                            ],
                            'medium' => [
                                'url' => 'https://i.ytimg.com/vi/gzqJZQyfkaI/mqdefault.jpg',
                                'width' => 320,
                                'height' => 180,
                            ],
                            'high' => [
                                'url' => 'https://i.ytimg.com/vi/gzqJZQyfkaI/hqdefault.jpg',
                                'width' => 480,
                                'height' => 360,
                            ],
                            'standard' => [
                                'url' => 'https://i.ytimg.com/vi/gzqJZQyfkaI/sddefault.jpg',
                                'width' => 640,
                                'height' => 480,
                            ],
                            'maxres' => [
                                'url' => 'https://i.ytimg.com/vi/gzqJZQyfkaI/maxresdefault.jpg',
                                'width' => 1280,
                                'height' => 720,
                            ],
                        ],
                        'channelTitle' => 'Freek Van der Herten',
                        'categoryId' => '24',
                        'liveBroadcastContent' => 'none',
                        'localized' => [
                            'title' => 'Live coding new features for larastreamers.com',
                            'description' => "Christoph Rumpel created a nice new project: https://larastreamers.com/\nIn this stream, I'm going to add some features to Christoph's app.",
                        ],
                    ],
                    'statistics' => [
                        'viewCount' => '687',
                        'likeCount' => '33',
                        'dislikeCount' => '0',
                        'favoriteCount' => '0',
                        'commentCount' => '1',
                    ],
                    'liveStreamingDetails' => [
                        'actualStartTime' => '2021-05-15T11:00:29Z',
                        'actualEndTime' => '2021-05-15T12:40:27Z',
                        'scheduledStartTime' => '2021-05-15T11:00:00Z',
                    ],
                ],
                1 => [
                    'kind' => 'youtube#video',
                    'etag' => 'yJ6zuQiV_kugObB-K_5gC18J_Xk',
                    'id' => 'bcnR4NYOw2o',
                    'snippet' => [
                        'publishedAt' => '2021-04-27T14:48:08Z',
                        'channelId' => 'UCNlUCA4VORBx8X-h-rXvXEg',
                        'title' => 'Laravel Worldwide Meetup #9: Exploring Tailwind\'s Headless UI / opendor.me behind the scenes',
                        'description' => 'https://meetup.laravel.com',
                        'thumbnails' => [
                            'default' => [
                                'url' => 'https://i.ytimg.com/vi/bcnR4NYOw2o/default_live.jpg',
                                'width' => 120,
                                'height' => 90,
                            ],
                            'medium' => [
                                'url' => 'https://i.ytimg.com/vi/bcnR4NYOw2o/mqdefault_live.jpg',
                                'width' => 320,
                                'height' => 180,
                            ],
                            'high' => [
                                'url' => 'https://i.ytimg.com/vi/bcnR4NYOw2o/hqdefault_live.jpg',
                                'width' => 480,
                                'height' => 360,
                            ],
                            'standard' => [
                                'url' => 'https://i.ytimg.com/vi/bcnR4NYOw2o/sddefault_live.jpg',
                                'width' => 640,
                                'height' => 480,
                            ],
                            'maxres' => [
                                'url' => 'https://i.ytimg.com/vi/bcnR4NYOw2o/maxresdefault_live.jpg',
                                'width' => 1280,
                                'height' => 720,
                            ],
                        ],
                        'channelTitle' => 'Freek Van der Herten',
                        'categoryId' => '24',
                        'liveBroadcastContent' => 'upcoming',
                        'localized' => [
                            'title' => 'Laravel Worldwide Meetup #9: Exploring Tailwind\'s Headless UI / opendor.me behind the scenes',
                            'description' => 'https://meetup.laravel.com',
                        ],
                    ],
                    'statistics' => [
                        'viewCount' => '0',
                        'likeCount' => '3',
                        'dislikeCount' => '0',
                        'favoriteCount' => '0',
                        'commentCount' => '0',
                    ],
                    'liveStreamingDetails' => [
                        'scheduledStartTime' => '2021-05-25T18:00:00Z',
                        'activeLiveChatId' => 'Cg0KC2JjblI0TllPdzJvKicKGFVDTmxVQ0E0Vk9SQng4WC1oLXJYdlhFZxILYmNuUjROWU93Mm8',
                    ],
                ],
                2 => [
                    'kind' => 'youtube#video',
                    'etag' => 'CrDHvGrQ1Q94ZFrWLN5Y8ueOHXI',
                    'id' => 'L3O1BbybSgw',
                    'snippet' => [
                        'publishedAt' => '2021-05-14T17:00:28Z',
                        'channelId' => 'UCNlUCA4VORBx8X-h-rXvXEg',
                        'title' => 'Casual Artisan Call #7',
                        'description' => 'Join Christoph Rumpel and me as we talk about life, the universe and everything',
                        'thumbnails' => [
                            'default' => [
                                'url' => 'https://i.ytimg.com/vi/L3O1BbybSgw/default_live.jpg',
                                'width' => 120,
                                'height' => 90,
                            ],
                            'medium' => [
                                'url' => 'https://i.ytimg.com/vi/L3O1BbybSgw/mqdefault_live.jpg',
                                'width' => 320,
                                'height' => 180,
                            ],
                            'high' => [
                                'url' => 'https://i.ytimg.com/vi/L3O1BbybSgw/hqdefault_live.jpg',
                                'width' => 480,
                                'height' => 360,
                            ],
                            'standard' => [
                                'url' => 'https://i.ytimg.com/vi/L3O1BbybSgw/sddefault_live.jpg',
                                'width' => 640,
                                'height' => 480,
                            ],
                            'maxres' => [
                                'url' => 'https://i.ytimg.com/vi/L3O1BbybSgw/maxresdefault_live.jpg',
                                'width' => 1280,
                                'height' => 720,
                            ],
                        ],
                        'channelTitle' => 'Freek Van der Herten',
                        'categoryId' => '24',
                        'liveBroadcastContent' => 'upcoming',
                        'localized' => [
                            'title' => 'Casual Artisan Call #7',
                            'description' => 'Join Christoph Rumpel and me as we talk about life, the universe and everything',
                        ],
                    ],
                    'statistics' => [
                        'viewCount' => '0',
                        'likeCount' => '0',
                        'dislikeCount' => '0',
                        'favoriteCount' => '0',
                        'commentCount' => '0',
                    ],
                    'liveStreamingDetails' => [
                        'scheduledStartTime' => '2021-05-21T09:00:00Z',
                        'activeLiveChatId' => 'Cg0KC0wzTzFCYnliU2d3KicKGFVDTmxVQ0E0Vk9SQng4WC1oLXJYdlhFZxILTDNPMUJieWJTZ3c',
                    ],
                ],
            ],
            'pageInfo' => [
                'totalResults' => 3,
                'resultsPerPage' => 3,
            ],
        ];
    }
}
