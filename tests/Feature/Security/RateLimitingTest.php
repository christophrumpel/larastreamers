<?php

use App\Livewire\SubmitYouTubeLiveStream;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;

it('enforces rate limiting on stream submissions', function() {
    // Arrange
    mockYouTubeVideoCall();
    RateLimiter::clear('stream-submission:' . request()->ip());

    // Act & Assert - First 3 submissions should work
    for ($i = 0; $i < 3; $i++) {
        Livewire::test(SubmitYouTubeLiveStream::class)
            ->set('youTubeIdOrUrl', "video{$i}")
            ->set('submittedByEmail', "test{$i}@test.at")
            ->call('submit')
            ->assertSee('You successfully submitted your stream');
    }

    // Fourth submission should be rate limited
    Livewire::test(SubmitYouTubeLiveStream::class)
        ->set('youTubeIdOrUrl', 'video4')
        ->set('submittedByEmail', 'test4@test.at')
        ->call('submit')
        ->assertHasErrors(['youTubeIdOrUrl' => 'Too many submission attempts']);
});

it('validates email format using strict validation', function() {
    // Arrange
    mockYouTubeVideoCall();

    // Act & Assert - Invalid email should fail
    Livewire::test(SubmitYouTubeLiveStream::class)
        ->set('youTubeIdOrUrl', 'bcnR4NYOw2o')
        ->set('submittedByEmail', 'not-an-email')
        ->call('submit')
        ->assertHasErrors(['submittedByEmail']);
});

it('accepts valid email addresses', function() {
    // Arrange
    mockYouTubeVideoCall();

    // Act & Assert
    Livewire::test(SubmitYouTubeLiveStream::class)
        ->set('youTubeIdOrUrl', 'bcnR4NYOw2o')
        ->set('submittedByEmail', 'valid@example.com')
        ->call('submit')
        ->assertHasNoErrors();
});

// Helper
function mockYouTubeVideoCall(string $videoId = null): void
{
    \App\Facades\YouTube::partialMock()
        ->shouldReceive('videos')
        ->andReturn(collect([
            \App\Services\YouTube\StreamData::fake(
                videoId: $videoId ?? 'bcnR4NYOw2o',
            ),
        ]));
}
