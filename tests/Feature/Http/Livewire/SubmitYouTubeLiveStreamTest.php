<?php

namespace Tests\Feature\Http\Livewire;

use App\Actions\Submission\SubmitStreamAction;
use App\Facades\YouTube;
use App\Http\Livewire\SubmitYouTubeLiveStream;
use App\Models\Stream;
use App\Services\YouTube\StreamData;
use Livewire\Livewire;
use Tests\TestCase;

class SubmitYouTubeLiveStreamTest extends TestCase
{
    /** @test */
    public function it_calls_the_submit_action(): void
    {
        // Arrange
        $this->mock(SubmitStreamAction::class)
            ->shouldReceive('handle')
            ->withArgs(['1234', 'de', 'test@test.at'])
            ->once();

        $this->mockYouTubVideoCall();

        // Arrange & Act & Assert
        Livewire::test(SubmitYouTubeLiveStream::class)
            ->set('youTubeIdOrUrl', '1234')
            ->set('submittedByEmail', 'test@test.at')
            ->set('languageCode', 'de')
            ->call('submit');
    }

    /** @test */
    public function it_calls_the_submit_action_with_full_youtube_url(): void
    {
        // Arrange
        $fullYoutubeUrl = 'https://www.youtube.com/watch?v=1234';
        $this->mock(SubmitStreamAction::class)
            ->shouldReceive('handle')
            ->withArgs(['1234', 'de', 'test@test.at'])
            ->once();

        $this->mockYouTubVideoCall();

        // Arrange & Act & Assert
        Livewire::test(SubmitYouTubeLiveStream::class)
            ->set('youTubeIdOrUrl', $fullYoutubeUrl)
            ->set('submittedByEmail', 'test@test.at')
            ->set('languageCode', 'de')
            ->call('submit');
    }

    /** @test */
    public function it_calls_the_submit_action_with_short_youtube_url(): void
    {
        // Arrange
        $shortYoutubeUrl = 'https://youtu.be/1234';
        $this->mock(SubmitStreamAction::class)
            ->shouldReceive('handle')
            ->withArgs(['1234', 'de', 'test@test.at'])
            ->once();

        $this->mockYouTubVideoCall();

        // Arrange & Act & Assert
        Livewire::test(SubmitYouTubeLiveStream::class)
            ->set('youTubeIdOrUrl', $shortYoutubeUrl)
            ->set('submittedByEmail', 'test@test.at')
            ->set('languageCode', 'de')
            ->call('submit');
    }

    /** @test */
    public function it_shows_a_success_message(): void
    {
        // Arrange
        $this->mockYouTubVideoCall();

        // Arrange & Act & Assert
        Livewire::test(SubmitYouTubeLiveStream::class)
            ->set('youTubeIdOrUrl', 'bcnR4NYOw2o')
            ->set('submittedByEmail', 'test@test.at')
            ->call('submit')
            ->assertSee('You successfully submitted your stream. You will receive an email, if it gets approved.');
    }

    /** @test */
    public function it_shows_a_success_message_with_full_youtube_url_too(): void
    {
        // Arrange
        $fullYoutubeUrl = 'https://www.youtube.com/watch?v=1234';
        $this->mockYouTubVideoCall();

        // Arrange & Act & Assert
        Livewire::test(SubmitYouTubeLiveStream::class)
            ->set('youTubeIdOrUrl', $fullYoutubeUrl)
            ->set('submittedByEmail', 'test@test.at')
            ->call('submit')
            ->assertSee('You successfully submitted your stream. You will receive an email, if it gets approved.');
    }

    /** @test */
    public function it_shows_errors_for_missing_youTubeIdOrUrl_or_email_fields(): void
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
            ->set('youTubeIdOrUrl', 'bcnR4NYOw2o')
            ->call('submit')
            ->assertSee('This stream was already submitted.');
    }

    /** @test */
    public function the_youtubeId_must_be_valid(): void
    {
        // Arrange
        YouTube::partialMock()
            ->shouldReceive('videos')
            ->andReturn(collect());

        // Arrange & Act & Assert
        Livewire::test(SubmitYouTubeLiveStream::class)
            ->set('submittedByEmail', 'test@test.at')
            ->set('youTubeIdOrUrl', 'not-valid-video-id')
            ->call('submit')
            ->assertSee('This is not a valid YouTube video id.');
    }

    /** @test */
    public function the_planned_stream_start_must_be_in_the_future(): void
    {
        // Arrange
        YouTube::partialMock()
            ->shouldReceive('videos')
            ->andReturn(collect([
                StreamData::fake(
                    videoId: 'bcnR4NYOw2o',
                    plannedStart: now()->subDay()
                ),
            ]));

        // Arrange & Act & Assert
        Livewire::test(SubmitYouTubeLiveStream::class)
            ->set('submittedByEmail', 'test@test.at')
            ->set('youTubeIdOrUrl', 'bcnR4NYOw2o')
            ->call('submit')
            ->assertSee('We only accept streams that have not started yet.');
    }

    /** @test */
    public function it_clears_out_the_form_after_submission(): void
    {
        // Arrange
        $this->mockYouTubVideoCall();

        // Arrange & Act & Assert
        Livewire::test(SubmitYouTubeLiveStream::class)
            ->set('youTubeIdOrUrl', 'bcnR4NYOw2o')
            ->set('submittedByEmail', 'test@test.at')
            ->set('languageCode', 'de')
            ->call('submit')
            ->assertSet('youTubeIdOrUrl', '')
            ->assertSet('languageCode', 'en')
            ->assertSet('submittedByEmail', '');
    }

    /** @test */
    public function it_wires_properties_and_methods(): void
    {
        // Arrange & Act & Assert
        Livewire::test(SubmitYouTubeLiveStream::class)
            ->assertPropertyWired('youTubeIdOrUrl')
            ->assertPropertyWired('submittedByEmail')
            ->assertPropertyWired('languageCode')
            ->assertMethodWiredToForm('submit');
    }

    private function mockYouTubVideoCall(string $videoId = 'bcnR4NYOw2o'): void
    {
        YouTube::partialMock()
            ->shouldReceive('videos')
            ->andReturn(collect([
                StreamData::fake(
                    videoId: $videoId,
                ),
            ]));
    }
}
