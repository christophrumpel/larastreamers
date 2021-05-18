<?php

namespace App\Services;

use App\Services\Youtube\ChannelData;
use App\Services\Youtube\StreamData;
use App\Services\Youtube\YoutubeException;
use Carbon\Carbon;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class YoutubeClient
{
    public function channel(string $id): ChannelData
    {
        $result = $this->fetch('channels', ['id' => $id, 'part' => 'snippet'], 'items.0');

        if (empty($result)) {
            throw YoutubeException::unknownChannel($id);
        }

        return new ChannelData(
            platformId: data_get($result, 'id'),
            slug: data_get($result, 'snippet.customUrl'),
            name: data_get($result, 'snippet.title'),
            description: data_get($result, 'snippet.description'),
            onPlatformSince: $this->toCarbon(data_get($result, 'snippet.publishedAt')),
            thumbnailUrl: last(data_get($result, 'snippet.thumbnails'))['url'] ?? null,
            country: data_get($result, 'snippet.country'),
        );
    }

    public function upcomingStreams(string $channelId): Collection
    {
        $videoIds = $this->fetch('search', [
            'channelId' => $channelId,
            'eventType' => 'upcoming',
            'type' => 'video',
            'part' => 'id',
            'maxResults' => 25,
        ], 'items.*.id.videoId');

        if (empty($videoIds)) {
            return collect();
        }

        return $this->videos($videoIds);
    }

    public function video(string $id): StreamData
    {
        return $this->videos($id)
            ->whenEmpty(fn() => throw YoutubeException::unknownVideo($id))
            ->first();
    }

    public function videos(iterable|string $videoIds): Collection
    {
        return collect($this->fetch('videos', [
            'id' => is_string($videoIds) ? $videoIds : collect($videoIds)->implode(','),
            'part' => 'snippet,statistics,liveStreamingDetails',
        ], 'items'))
            ->map(fn(array $item) => new StreamData(
                videoId: data_get($item, 'id'),
                title: data_get($item, 'snippet.title'),
                channelTitle: data_get($item, 'snippet.channelTitle'),
                description: data_get($item, 'snippet.description'),
                thumbnailUrl: last(data_get($item, 'snippet.thumbnails'))['url'] ?? null,
                publishedAt: $this->toCarbon(data_get($item, 'snippet.publishedAt')),
                plannedStart: $this->getPlannedStart($item),
                status: data_get($item, 'snippet.liveBroadcastContent'),
            ));
    }

    protected function getPlannedStart(array $data): Carbon
    {
        return $this->toCarbon(data_get($data, 'liveStreamingDetails.scheduledStartTime'))
            ?? $this->toCarbon(data_get($data, 'snippet.publishedAt'));
    }

    protected function fetch(string $url, array $params = [], string $key = null): array
    {
        return Http::asJson()
            ->baseUrl('https://youtube.googleapis.com/youtube/v3/')
            ->get($url, array_merge($params, [
                'key' => config('services.youtube.key'),
            ]))
            ->onError(fn(Response $response) => throw YoutubeException::general($response->status()))
            ->json($key, []);
    }

    protected function toCarbon(?string $string): ?Carbon
    {
        if (empty($string)) {
            return null;
        }

        return Carbon::parse($string);
    }
}
