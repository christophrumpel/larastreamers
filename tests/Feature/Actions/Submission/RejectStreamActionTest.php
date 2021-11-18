<?php

use App\Actions\Submission\RejectStreamAction;
use App\Mail\StreamRejectedMail;
use App\Models\Stream;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

uses(TestCase::class);

test('the action sends rejection mail', function () {
    // Arrange
    Mail::fake();
    $stream = Stream::factory()
        ->notApproved()
        ->create([
            'submitted_by_email' => 'john@example.com',
        ]);

    // Act
    $action = app(RejectStreamAction::class);
    $action->handle($stream);

    // Assert
    Mail::assertQueued(fn(StreamRejectedMail $mail) => $mail->hasTo($stream->submitted_by_email));
    expect($stream->refresh()->isApproved())->toBeFalse();
});
