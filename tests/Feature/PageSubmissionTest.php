<?php

namespace Tests\Feature;

use App\Http\Livewire\SubmitYouTubeLiveStream;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageSubmissionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_includes_the_submission_livewire_component(): void
    {
        $this->get(route('submission'))
            ->assertSeeLivewire(SubmitYouTubeLiveStream::class);
    }
}
