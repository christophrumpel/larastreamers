<?php

namespace Tests\Feature\Actions\Submission;

use App\Actions\Submission\RejectStreamAction;
use App\Mail\StreamRejectedMail;
use App\Models\Stream;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RejectStreamActionTest extends TestCase
{
    /** @test */
    public function the_action_sends_rejection_mail(): void
    {
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
        $this->assertFalse($stream->refresh()->isApproved());
    }
}
