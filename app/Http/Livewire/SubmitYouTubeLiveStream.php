<?php

namespace App\Http\Livewire;

use App\Actions\Submission\SubmitStreamAction;
use App\Rules\YouTubeRule;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Component;

class SubmitYouTubeLiveStream extends Component
{

    public $youTubeIdOrUrl;

    public $submittedByEmail;

    public string $languageCode = 'en';

    protected $messages = [
        'youTubeIdOrUrl.required' => 'The YouTube ID field cannot be empty.',
        'youTubeIdOrUrl.unique' => 'This stream was already submitted.',
        'submittedByEmail.required' => 'The Email field cannot be empty.',
    ];

    public function rules(): array
    {
        return [
            'youTubeIdOrUrl' => ['required', Rule::unique('streams', 'youtube_id'), new YouTubeRule()],
            'submittedByEmail' => 'required',
        ];
    }

    public function render(): View
    {
        return view('livewire.submit-you-tube-live-stream');
    }

    public function submit(): void
    {
        if(filter_var($this->youTubeIdOrUrl, FILTER_VALIDATE_URL)){
            $query = parse_url($this->youTubeIdOrUrl, PHP_URL_QUERY);
            parse_str($query, $result);
            $youTubeId = $result['v'];
        } else {
            $youTubeId = $this->youTubeIdOrUrl;
        }

        $this->validate();

        $action = app(SubmitStreamAction::class);
        $action->handle($youTubeId, $this->languageCode, $this->submittedByEmail);

        session()->flash('message', 'You successfully submitted your stream. You will receive an email, if it gets approved.');
        $this->reset(['youTubeIdOrUrl', 'languageCode', 'submittedByEmail']);
    }
}
