<?php

namespace App\Rules;

use App\Facades\Youtube;
use App\Services\Youtube\YoutubeException;
use Illuminate\Contracts\Validation\Rule;

class YouTubeRule implements Rule
{
    protected $message = '';

    public function passes($attribute, $value)
    {
        try {
            $video = Youtube::video($value);
        } catch (YoutubeException) {
            $this->message = 'This is not a valid YouTube video id';

            return false;
        }

        if (is_null($video)) {
            $this->message = 'This is not a valid YouTube video id';

            return false;
        }

        if (! $video->plannedStart->isFuture()) {
            $this->message = 'We only accept streams that have not started yet';

            return false;
        }

        return true;
    }

    public function message()
    {
        return $this->message;
    }
}
