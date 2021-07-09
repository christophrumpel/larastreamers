<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StreamResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->youtube_id,
            'type' => 'stream',
            'attributes' => [
                'title' => $this->title,
                'description' => $this->description,
                'channel' => $this->channel_title,
                'thumbnail_url' => $this->thumbnail_url,
                'starts' => [
                    'human' => $this->scheduled_start_time->diffForHumans(),
                    'string' => $this->scheduled_start_time->toDateTimeString(),
                    'formatted' => $this->scheduled_start_time->format('D d.m.Y')
                ],
                'status' => $this->status,
                'language' => $this->language_code,
                'live' => $this->isLive(),
            ]
        ];
    }
}
