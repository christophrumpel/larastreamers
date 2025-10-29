<?php

namespace App\Rules;

use App\Facades\YouTube;
use App\Services\YouTube\YouTubeException;
use Illuminate\Contracts\Validation\Rule;

class YouTubeRule implements Rule
{
    protected string $message = '';

    public function passes($attribute, $value): bool
    {
        $youTubeId = $this->determineYoutubeId($value);

        if (empty($youTubeId)) {
            $this->message = 'This is not a valid YouTube video ID/URL.';

            return false;
        }

        try {
            $video = YouTube::video($youTubeId);
        } catch (YouTubeException) {
            $this->message = "We couldn't find a YouTube video for the ID: $youTubeId";

            return false;
        }

        return true;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function determineYoutubeId(string $youTubeIdOrUrl): string
    {
        if (filter_var($youTubeIdOrUrl, FILTER_VALIDATE_URL)) {
            preg_match('#(?<=v=|v/|vi=|vi/|youtu.be/|live/)[a-zA-Z0-9_-]{11}#', $youTubeIdOrUrl, $matches);
            if (! $matches) {
                return '';
            }

            return $matches[0];
        }

        return $youTubeIdOrUrl;
    }
}
