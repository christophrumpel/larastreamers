<?php

namespace Tests\Fakes;

use App\Enums\TwitchEventType;
use App\Models\Channel;

trait TwitchResponses
{

    public function userResponse(): array
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

    public function twitchChannelResponse(): array
    {
        return [
            "data" => [
                [
                    "broadcaster_id" => "1234",
                    "broadcaster_login" => "christophrumpel",
                    "broadcaster_name" => "christophrumpel",
                    "broadcaster_language" => "en",
                    "game_id" => "509670",
                    "game_name" => "Science & Technology",
                    "title" => "📺 Redesigning Larastreamers ",
                    "delay" => 0,
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

    public function verifyEventSubscriptionPayload(TwitchEventType $event, Channel $channel): array
    {
        return [
            "challenge" => "59939e90-d3bc-6662-ec66-e85ab2c013ee",
            "subscription" => [
                "id" => "1234",
                "status" => "enabled",
                "type" => $event->value,
                "version" => "1",
                "cost" => 1,
                "condition" => [
                    "broadcaster_user_id" => $channel->platform_id,
                ],
                "transport" => [
                    "method" => "webhook",
                    "callback" => route('webhooks')
                ],
                "created_at" => "2019-11-16T10=>11=>12.123Z"
            ],
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

    public function streamOnlineEventPayload(Channel $channel): array
    {
        return [
            "subscription" => [
                "id" => "1234",
                "status" => "enabled",
                "type" => "stream.online",
                "version" => "1",
                "cost" => 1,
                "condition" => [
                    "broadcaster_user_id" => $channel->platform_id,
                ],
                "transport" => [
                    "method" => "webhook",
                    "callback" => route('webhooks')
                ],
                "created_at" => "2019-11-16T10=>11=>12.123Z"
            ],
            "event" => [
                "user_id" => "9999",
                "user_login" => "christophrumpel",
                "user_name" => "Christoph Rumpel",
                "broadcaster_user_id" => "1234",
                "broadcaster_user_login" => "twitch",
                "broadcaster_user_name" => "Twitch"
            ]
        ];
    }

}
