<?php

namespace Tests\Feature\Livewire;

use App\Actions\Submission\SubmitStreamAction;
use App\Facades\Youtube;
use App\Http\Livewire\SubmitYouTubeLiveStream;
use App\Services\Youtube\StreamData;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubmitYouTubeLiveStreamTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_calls_the_submit_action(): void
    {
        // Arrange
        $this->mock(SubmitStreamAction::class)
            ->shouldReceive('handle')
            ->once();

        Youtube::partialMock()
            ->shouldReceive('videos')
            ->andReturn(collect([StreamData::fake(
                videoId: 'bcnR4NYOw2o',
            )]));

        // Arrange & Act & Assert
        Livewire::test(SubmitYouTubeLiveStream::class)
            ->set('youTubeId', 'bcnR4NYOw2o')
            ->set('submittedByEmail', 'test@test.at')
            ->call('submit')
            ->assertSee('You successfully submitted your stream.');
    }

    /** @test */
    public function it_wires_properties_and_methods(): void
    {
        // Arrange & Act & Assert
        Livewire::test(SubmitYouTubeLiveStream::class)
            ->assertPropertyWired('youTubeId')
            ->assertPropertyWired('submittedByEmail')
            ->assertMethodWiredToForm('submit');
    }

}
