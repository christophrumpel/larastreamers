<?php

namespace App\Livewire;

use App\Actions\Submission\SubmitStreamAction;
use App\Rules\YouTubeRule;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class SubmitYouTubeLiveStream extends Component
{
    public string $youTubeIdOrUrl = '';

    public string $submittedByEmail = '';

    public string $languageCode = 'en';

    protected array $messages = [
        'youTubeIdOrUrl.required' => 'The YouTube ID field cannot be empty.',
        'youTubeIdOrUrl.unique' => 'This stream was already submitted.',
        'submittedByEmail.required' => 'The Email field cannot be empty.',
        'submittedByEmail.email' => 'Please provide a valid email address.',
    ];

    public function rules(): array
    {
        return [
            'youTubeIdOrUrl' => ['required', Rule::unique('streams', 'youtube_id'), new YouTubeRule],
            'submittedByEmail' => ['required', 'email:rfc,dns'],
        ];
    }

    public function render(): View
    {
        return view('livewire.submit-you-tube-live-stream');
    }

    public function submit(): void
    {
        // Rate limiting: max 3 submissions per hour per IP
        $key = 'stream-submission:' . request()->ip();
        
        if (RateLimiter::tooManyAttempts($key, 3)) {
            throw ValidationException::withMessages([
                'youTubeIdOrUrl' => 'Too many submission attempts. Please try again later.',
            ]);
        }

        $this->validate();

        RateLimiter::hit($key, 3600); // 1 hour decay

        $action = app(SubmitStreamAction::class);
        $action->handle((new YouTubeRule)->determineYoutubeId($this->youTubeIdOrUrl), $this->languageCode, $this->submittedByEmail);

        session()->flash('message', 'You successfully submitted your stream. You will receive an email, if it gets approved.');
        $this->reset(['youTubeIdOrUrl', 'languageCode', 'submittedByEmail']);
    }
}
