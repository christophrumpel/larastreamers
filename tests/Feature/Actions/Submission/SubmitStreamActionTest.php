<?php

use App\Actions\Submission\SubmitStreamAction;
use App\Facades\YouTube;
use App\Mail\StreamSubmittedMail;
use App\Models\Stream;
use App\Services\YouTube\StreamData;
use Illuminate\Support\Facades\Mail;


it('can store a stream', function () {
    // Arrange
    Mail::fake();
    YouTube::partialMock()
        ->shouldReceive('videos')
        ->andReturn(collect([
            StreamData::fake(
                videoId: '1234',
            ),
        ]));

    // Act
    $action = app(SubmitStreamAction::class);
    $action->handle('1234', 'de', 'john@example.com');

    // Assert
    $stream = Stream::firstWhere('youtube_id', '1234');
    $this->assertNotNull($stream);

    expect($stream)
        ->isApproved()->toBeFalse()
        ->submitted_by_email->toBe('john@example.com')
        ->language_code->toBe('de');

    Mail::assertQueued(fn(StreamSubmittedMail $mail) => $mail->hasTo('christoph@christoph-rumpel.com'));
});
