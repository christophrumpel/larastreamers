<?php

namespace App\Rules;

use App\Facades\YouTube;
use App\Services\YouTube\YouTubeException;
use Illuminate\Contracts\Validation\Rule;

class YouTubeRule implements Rule
{
    protected $message = '';

    public function passes($attribute, $value): bool
    {
        try {
            $video = YouTube::video($value);
        } catch (YouTubeException) {
            $this->message = 'This is not a valid YouTube video id.';

            return false;
        }

        if (is_null($video)) {
            $this->message = 'This is not a valid YouTube video id.';

            return false;
        }

        if (! $video->plannedStart->isFuture()) {
            $this->message = 'We only accept streams that have not started yet.';

            return false;
        }

        return true;
    }

    public function message(): string
    {
        return $this->message;
    }
}
