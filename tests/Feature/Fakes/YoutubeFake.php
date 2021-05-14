<?php


namespace Tests\Feature\Fakes;


use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class YoutubeFake
{

    private string $upcoming = 'upcoming';
    private string $youtubeId;
    private string $title = 'My Test Stream';
    private string $channelTitle = 'My Test Channel';
    private string $thumbnailUrl = '';

    public function __construct(public ?Carbon $scheduledStartTime = null)
    {
        $this->youtubeId = Str::random(10);
    }

    public function setYoutubeId(string $youtubeId): self
    {
        $this->youtubeId = $youtubeId;

        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function setChannelTitle(string $title): self
    {
        $this->channelTitle = $title;

        return $this;
    }

    public function setThumbnailUrl(string $thumbnailUrl): self
    {
        $this->thumbnailUrl = $thumbnailUrl;

        return $this;
    }

    public function setUpcomingFalse(): self
    {
        $this->upcoming = '';

        return $this;
    }

    public function getVideoInfo($vId, $part = ['id', 'snippet', 'contentDetails', 'player', 'statistics', 'status'])
    {
        return $this->getTestApiData();
    }

    private function getTestApiData()
    {
        if (!$this->scheduledStartTime) {
            $this->scheduledStartTime = Carbon::tomorrow();
        }

        $scheduledStartTime = $this->scheduledStartTime->toIso8601String();

        return json_decode('{
            "id": "' . $this->youtubeId.'",
            "snippet": {
                "title": "' . $this->title . '",
                "channelTitle": "'.$this->channelTitle.'",
                "liveBroadcastContent": "' . $this->upcoming . '",
                "thumbnails": {
                    "maxres": {
                        "url": "'.$this->thumbnailUrl.'"
                    }
                }
            },
            "liveStreamingDetails": {
                "scheduledStartTime": "' . $scheduledStartTime . '"
                }
            }');
    }
}
