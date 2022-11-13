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

    public function subscriptionOnlineResponse(): array
    {
        return [
            "data" => [
                [
                    "id" => "bc780471-e3e4-460c-a724-df4317e6594c",
                    "status" => "webhook_callback_verification_pending",
                    "type" => "stream.online",
                    "version" => "1",
                    "condition" => [
                        "broadcaster_user_id" => "1234",
                    ],
                    "created_at" => "2022-11-11T14:06:05.984219853Z",
                    "transport" => [
                        "method" => "webhook",
                        "callback" => "https://webhook.call",
                    ],
                    "cost" => 1,
                ]
            ],
            "total" => 2,
            "max_total_cost" => 10000,
            "total_cost" => 1,
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
