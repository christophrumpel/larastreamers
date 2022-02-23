<?php

namespace App\Services;

use App\Services\YouTube\ChannelData;
use App\Services\YouTube\StreamData;
use App\Services\YouTube\YouTubeException;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class YouTubeClient
{
    public function channel(string $id): ChannelData
    {
        return $this->channels($id)
            ->whenEmpty(fn() => throw  YouTubeException::unknownChannel($id))
            ->first();
    }

    public function channels(iterable|string $channelIds): Collection
    {
        return Http::youtube('channels', [
            /** @phpstan-ignore-next-line   */
            'id' => collect($channelIds)->implode(','),
            'part' => 'snippet',
        ], 'items')->map(fn(array $item) => new ChannelData(
            platformId: data_get($item, 'id'),
            youTubeCustomUrl: data_get($item, 'snippet.customUrl', ''),
            name: data_get($item, 'snippet.title'),
            description: data_get($item, 'snippet.description', ''),
            onPlatformSince: $this->toCarbon(data_get($item, 'snippet.publishedAt')),
            thumbnailUrl: last(data_get($item, 'snippet.thumbnails'))['url'] ?? null,
            country: data_get($item, 'snippet.country', ''),
        ));
    }

    public function upcomingStreams(string $channelId): Collection
    {
        return Http::youtube('search', [
            'channelId' => $channelId,
            'eventType' => 'upcoming',
            'type' => 'video',
            'part' => 'id',
            'maxResults' => 25,
        ], 'items.*.id.videoId')->whenNotEmpty(fn(Collection $videoIds) => $this->videos($videoIds));
    }

    public function video(string $id): StreamData
    {
        return $this->videos($id)
            ->whenEmpty(fn() => throw YouTubeException::unknownVideo($id))
            ->first();
    }

    public function videos(iterable|string $videoIds): Collection
    {
        return Http::youtube('videos', [
            /** @phpstan-ignore-next-line   */
            'id' => collect($videoIds)->implode(','),
            'part' => 'snippet,statistics,liveStreamingDetails',
        ], 'items')
            ->map(fn(array $youTubeVideoDetails) => new StreamData(
                videoId: data_get($youTubeVideoDetails, 'id'),
                title: data_get($youTubeVideoDetails, 'snippet.title'),
                channelId: data_get($youTubeVideoDetails, 'snippet.channelId'),
                channelTitle: data_get($youTubeVideoDetails, 'snippet.channelTitle'),
                description: data_get($youTubeVideoDetails, 'snippet.description'),
                thumbnailUrl: last(data_get($youTubeVideoDetails, 'snippet.thumbnails'))['url'] ?? null,
                publishedAt: $this->toCarbon(data_get($youTubeVideoDetails, 'snippet.publishedAt')),
                plannedStart: $this->getPlannedStart($youTubeVideoDetails),
                actualStartTime: $this->toCarbon(data_get($youTubeVideoDetails, 'liveStreamingDetails.actualStartTime')),
                actualEndTime: $this->toCarbon(data_get($youTubeVideoDetails, 'liveStreamingDetails.actualEndTime')),
                status: $this->getStatusFromYouVideoDetails($youTubeVideoDetails),
            ));
    }

    protected function getPlannedStart(array $data): ?Carbon
    {
        return $this->toCarbon(data_get($data, 'liveStreamingDetails.scheduledStartTime', now()))
            ?? $this->toCarbon(data_get($data, 'snippet.publishedAt', now()));
    }

    protected function toCarbon(?string $string): ?Carbon
    {
        if (empty($string)) {
            return null;
        }

        return Carbon::parse($string);
    }

    protected function getStatusFromYouVideoDetails(array $youTubeVideoDetails): string
    {
        $youTubeStatus = data_get($youTubeVideoDetails, 'snippet.liveBroadcastContent');
        if ($youTubeStatus === 'none') {
            return StreamData::STATUS_FINISHED;
        }

        return $youTubeStatus;
    }
}
