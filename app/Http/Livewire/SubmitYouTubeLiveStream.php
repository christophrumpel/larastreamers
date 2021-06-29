<?php

namespace App\Http\Livewire;

use App\Actions\Submission\SubmitStreamAction;
use App\Rules\YouTubeRule;
use Illuminate\Validation\Rule;
use Livewire\Component;

class SubmitYouTubeLiveStream extends Component
{

    public $youTubeId;

    public $submittedByEmail;

    protected $messages = [
        'youTubeId.required' => 'The YouTube ID field cannot be empty.',
        'youTubeId.unique' => 'This stream was already submitted.',
        'submittedByEmail.required' => 'The Email field cannot be empty.',
    ];

    public function rules(): array
    {
        return [
            'youTubeId' => ['required', Rule::unique('streams', 'youtube_id'), new YouTubeRule()],
            'submittedByEmail' => 'required'
        ];
    }

    public function render()
    {
        return view('livewire.submit-you-tube-live-stream');
    }

    public function submit(): void
    {
        $this->validate();

        $action = app(SubmitStreamAction::class);
        $action->handle($this->youTubeId, $this->submittedByEmail);

        session()->flash('message', 'You successfully submitted your stream.');

    }
}
