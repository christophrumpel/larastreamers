<?php

namespace Tests\Fakes;

trait TwitchResponses
{

    public function channelResponse(): array
    {
        return [
            "data" => [
                [
                    "id" => "1234",
                    "login" => "christophrumpel",
                    "display_name" => "Christoph Rumpel",
                    "description" => "my description",
                    "profile_image_url" => "https://profile.url",
                ]
            ]
        ];
    }

    public function scheduleResponse(): array
    {
        return [
            'data' => [
                'segments' => [
                    [
                        "id" => "4567",
                        "start_time" => "2022-11-11T01:00:00Z",
                        "end_time" => "2022-11-11T04:00:00Z",
                        "title" => "stream title",
                    ],
                    [
                        "id" => "899",
                        "start_time" => "2022-11-12T01:00:00Z",
                        "end_time" => "2022-11-12T04:00:00Z",
                        "title" => "another stream title",
                    ]
                ]
            ]
        ];
    }

}
