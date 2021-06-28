<?php

namespace App\Http\Livewire;

use App\Actions\Submission\SubmitStreamAction;
use Livewire\Component;

class SubmitYouTubeLiveStream extends Component
{

    public $youTubeId;

    public $submittedByEmail;

    public function render()
    {
        return view('livewire.submit-you-tube-live-stream');
    }

    public function submit(): void
    {
        $action = app(SubmitStreamAction::class);
        $action->handle($this->youTubeId, $this->submittedByEmail);

        session()->flash('message', 'You successfully submitted your stream.');

    }
}
