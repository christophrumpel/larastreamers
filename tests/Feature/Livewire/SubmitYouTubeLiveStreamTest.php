<?php

namespace Tests\Feature\Livewire;

use App\Actions\Submission\SubmitStreamAction;
use App\Facades\Youtube;
use App\Http\Livewire\SubmitYouTubeLiveStream;
use App\Models\Stream;
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
            ->call('submit');
    }

    /** @test */
    public function it_shows_a_success_message(): void
    {
        // Arrange
        $this->mockYouTubVideoCall();

        // Arrange & Act & Assert
        Livewire::test(SubmitYouTubeLiveStream::class)
            ->set('youTubeId', 'bcnR4NYOw2o')
            ->set('submittedByEmail', 'test@test.at')
            ->call('submit')
            ->assertSee('You successfully submitted your stream.');
    }

    /** @test */
    public function it_shows_errors_for_missing_youTubeId_or_email_fields(): void
    {
        // Arrange
        $this->mockYouTubVideoCall();

        // Arrange & Act & Assert
        Livewire::test(SubmitYouTubeLiveStream::class)
            ->call('submit')
            ->assertSee('The YouTube ID field cannot be empty.')
            ->assertSee('The Email field cannot be empty.');
    }

    /** @test */
    public function the_youtubeId_must_be_unique(): void
    {
        // Arrange
        Stream::factory()->create(['youtube_id' => 'bcnR4NYOw2o']);
        $this->mockYouTubVideoCall();

        // Arrange & Act & Assert
        Livewire::test(SubmitYouTubeLiveStream::class)
            ->set('youTubeId', 'bcnR4NYOw2o')
            ->call('submit')
            ->assertSee('This stream was already submitted.');
    }

    /** @test */
    public function the_youtubeId_must_be_valid(): void
    {
        // Arrange
        Youtube::partialMock()
            ->shouldReceive('videos')
            ->andReturn(collect());

        // Arrange & Act & Assert
        Livewire::test(SubmitYouTubeLiveStream::class)
            ->set('submittedByEmail', 'test@test.at')
            ->set('youTubeId', 'not-valid-video-id')
            ->call('submit')
            ->assertSee('This is not a valid YouTube video id.');
    }

    /** @test */
    public function the_planned_stream_start_must_be_in_the_future(): void
    {
        // Arrange
        Youtube::partialMock()
            ->shouldReceive('videos')
            ->andReturn(collect([
                StreamData::fake(
                    videoId: 'bcnR4NYOw2o',
                    plannedStart: now()->subDay()
                )
            ]));

        // Arrange & Act & Assert
        Livewire::test(SubmitYouTubeLiveStream::class)
            ->set('submittedByEmail', 'test@test.at')
            ->set('youTubeId', 'not-valid-video-id')
            ->call('submit')
            ->assertSee('We only accept streams that have not started yet.');
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

    private function mockYouTubVideoCall(string $videoId = 'bcnR4NYOw2o'): void
    {
        Youtube::partialMock()
            ->shouldReceive('videos')
            ->andReturn(collect([
                StreamData::fake(
                    videoId: $videoId,
                )
            ]));
    }

}
