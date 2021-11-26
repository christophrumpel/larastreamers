<?php

namespace App\Http\Resources;

use App\Models\Stream;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Stream
 */
class StreamResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'type' => 'stream',
            'attributes' => [
                'title' => $this->title,
                'description' => $this->description,
                'thumbnail_url' => $this->thumbnail_url,
                'starts' => [
                    'human' => $this->scheduled_start_time->diffForHumans(),
                    'string' => $this->scheduled_start_time->toDateTimeString(),
                    'formatted' => $this->scheduled_start_time->format('D d.m.Y'),
                ],
                'status' => $this->status,
                'language_code' => $this->language_code,
                'live' => $this->isLive(),
                'identifiers' => [
                    'youtube' => $this->youtube_id,
                ],
            ],
        ];
    }
}
