<?php

use App\Actions\Submission\SubmitStreamAction;
use App\Facades\YouTube;
use App\Mail\StreamSubmittedMail;
use App\Models\Stream;
use App\Services\YouTube\StreamData;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

uses(TestCase::class);

it('can store a stream', function () {
    // Arrange
    Mail::fake();
    YouTube::partialMock()
        ->shouldReceive('videos')
        ->andReturn(collect([
            StreamData::fake(
                videoId: $this->youTubeId,
            ),
        ]));

    // Act
    $action = app(SubmitStreamAction::class);
    $action->handle($this->youTubeId, 'de', 'john@example.com');

    // Assert
    $stream = Stream::firstWhere('youtube_id', $this->youTubeId);
    $this->assertNotNull($stream);

    expect($stream->isApproved())->toBeFalse();
    expect($stream->submitted_by_email)->toEqual('john@example.com');
    expect($stream->language_code)->toEqual('de');

    Mail::assertQueued(fn(StreamSubmittedMail $mail) => $mail->hasTo('christoph@christoph-rumpel.com'));
});
