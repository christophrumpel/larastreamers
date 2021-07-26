<?php

namespace Tests\Feature\Actions\Submission;

use App\Actions\Submission\ApproveStreamAction;
use App\Mail\StreamApprovedMail;
use App\Models\Stream;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ApproveStreamActionTest extends TestCase
{
    use RefreshDatabase;

    protected ApproveStreamAction $approveStream;

    public function setUp(): void
    {
        parent::setUp();

        Mail::fake();

        $this->approveStream = app(ApproveStreamAction::class);
    }

    /** @test */
    public function the_action_can_approve_a_stream(): void
    {
        $stream = Stream::factory()->create([
            'approved_at' => null,
            'submitted_by_email' => 'john@example.com',
        ]);

        $this->approveStream->handle($stream);

        $stream = $stream->fresh();

        $this->assertNotNull($stream->approved_at);

        Mail::assertQueued(fn(StreamApprovedMail $mail) => $mail->hasTo($stream->submitted_by_email));
    }

    /** @test */
    public function it_will_not_send_a_mail_for_a_link_that_was_already_approved(): void
    {
        $stream = Stream::factory()->create([
            'approved_at' => now(),
            'submitted_by_email' => 'john@example.com',
        ]);

        $this->approveStream->handle($stream);

        Mail::assertNothingQueued();
    }
}
