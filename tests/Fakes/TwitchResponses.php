<?php

namespace Tests\Fakes;

trait TwitchResponses {

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

}
