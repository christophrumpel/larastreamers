<?php

use App\Actions\Submission\SubmitStreamAction;
use App\Facades\YouTube;
use App\Http\Livewire\SubmitYouTubeLiveStream;
use App\Models\Stream;
use App\Services\YouTube\StreamData;
use Livewire\Livewire;

it('calls the submit action', function() {
    // Arrange
    $this->mock(SubmitStreamAction::class)
        ->shouldReceive('handle')
        ->withArgs(['1234', 'de', 'test@test.at'])
        ->once();

    mockYouTubVideoCall();

    // Arrange & Act & Assert
    Livewire::test(SubmitYouTubeLiveStream::class)
        ->set('youTubeIdOrUrl', '1234')
        ->set('submittedByEmail', 'test@test.at')
        ->set('languageCode', 'de')
        ->call('submit');
});

it('calls the submit action with full youtube url', function() {
    // Arrange
    $fullYoutubeUrl = 'https://www.youtube.com/watch?v=bcnR4NYOw2o';
    $this->mock(SubmitStreamAction::class)
        ->shouldReceive('handle')
        ->withArgs(['bcnR4NYOw2o', 'de', 'test@test.at'])
        ->once();

    mockYouTubVideoCall();

    // Arrange & Act & Assert
    Livewire::test(SubmitYouTubeLiveStream::class)
        ->set('youTubeIdOrUrl', $fullYoutubeUrl)
        ->set('submittedByEmail', 'test@test.at')
        ->set('languageCode', 'de')
        ->call('submit');
});

it('calls the submit action with short youtube url', function() {
    // Arrange
    $shortYoutubeUrl = 'https://youtu.be/bcnR4NYOw2o';
    $this->mock(SubmitStreamAction::class)
        ->shouldReceive('handle')
        ->withArgs(['bcnR4NYOw2o', 'de', 'test@test.at'])
        ->once();

    mockYouTubVideoCall();

    // Arrange & Act & Assert
    Livewire::test(SubmitYouTubeLiveStream::class)
        ->set('youTubeIdOrUrl', $shortYoutubeUrl)
        ->set('submittedByEmail', 'test@test.at')
        ->set('languageCode', 'de')
        ->call('submit');
});

it('shows a success message', function() {
    // Arrange
    mockYouTubVideoCall();

    // Arrange & Act & Assert
    Livewire::test(SubmitYouTubeLiveStream::class)
        ->set('youTubeIdOrUrl', 'bcnR4NYOw2o')
        ->set('submittedByEmail', 'test@test.at')
        ->call('submit')
        ->assertSee('You successfully submitted your stream. You will receive an email, if it gets approved.');
});

it('shows a success message with full youtube url too', function() {
    // Arrange
    $fullYoutubeUrl = 'https://www.youtube.com/watch?v=bcnR4NYOw2o';
    mockYouTubVideoCall();

    // Arrange & Act & Assert
    Livewire::test(SubmitYouTubeLiveStream::class)
        ->set('youTubeIdOrUrl', $fullYoutubeUrl)
        ->set('submittedByEmail', 'test@test.at')
        ->call('submit')
        ->assertSee('You successfully submitted your stream. You will receive an email, if it gets approved.');
});

it('shows errors for wrong you tube url', function() {
    // Arrange
    mockYouTubVideoCall();

    // Arrange & Act & Assert
    Livewire::test(SubmitYouTubeLiveStream::class)
        ->set('youTubeIdOrUrl', 'https://twitch.com/video?v=1')
        ->call('submit')
        ->assertSee('This is not a valid YouTube video ID/URL.');
});

test('the youtube id must be unique', function() {
    // Arrange
    Stream::factory()->create(['youtube_id' => 'bcnR4NYOw2o']);
    mockYouTubVideoCall();

    // Arrange & Act & Assert
    Livewire::test(SubmitYouTubeLiveStream::class)
        ->set('youTubeIdOrUrl', 'bcnR4NYOw2o')
        ->call('submit')
        ->assertSee('This stream was already submitted.');
});

test('the youtube id must be valid', function() {
    // Arrange
    YouTube::partialMock()
        ->shouldReceive('videos')
        ->andReturn(collect());

    // Arrange & Act & Assert
    Livewire::test(SubmitYouTubeLiveStream::class)
        ->set('submittedByEmail', 'test@test.at')
        ->set('youTubeIdOrUrl', 'not-valid-video-id')
        ->call('submit')
        ->assertSee("We couldn't find a YouTube video for the ID: not-valid-video-id");
});

test('the planned stream start must be in the future', function() {
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
});

it('clears out the form after submission', function() {
    // Arrange
    mockYouTubVideoCall();

    // Arrange & Act & Assert
    Livewire::test(SubmitYouTubeLiveStream::class)
        ->set('youTubeIdOrUrl', 'bcnR4NYOw2o')
        ->set('submittedByEmail', 'test@test.at')
        ->set('languageCode', 'de')
        ->call('submit')
        ->assertSet('youTubeIdOrUrl', '')
        ->assertSet('languageCode', 'en')
        ->assertSet('submittedByEmail', '');
});

it('wires properties and methods', function() {
    // Arrange & Act & Assert
    Livewire::test(SubmitYouTubeLiveStream::class)
        ->assertPropertyWired('youTubeIdOrUrl')
        ->assertPropertyWired('submittedByEmail')
        ->assertPropertyWired('languageCode')
        ->assertMethodWiredToForm('submit');
});

// Helpers
function mockYouTubVideoCall(string $videoId = 'bcnR4NYOw2o'): void
{
    YouTube::partialMock()
        ->shouldReceive('videos')
        ->andReturn(collect([
            StreamData::fake(
                videoId: $videoId,
            ),
        ]));
}
