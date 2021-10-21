<?php

namespace Tests\Feature\Actions\Submission;

use App\Actions\Submission\ApproveStreamAction;
use App\Console\Commands\ImportChannelsForStreamsCommand;
use App\Mail\StreamApprovedMail;
use App\Models\Stream;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ApproveStreamActionTest extends TestCase
{
    protected ApproveStreamAction $approveStreamAction;

    public function setUp(): void
    {
        parent::setUp();

        Mail::fake();

        $this->approveStreamAction = app(ApproveStreamAction::class);
    }

    /** @test */
    public function the_action_can_approve_a_stream(): void
    {
        // Arrange
        $stream = Stream::factory()
            ->notApproved()
            ->create([
                'submitted_by_email' => 'john@example.com',
            ]);

        Artisan::spy();

        // Act
        $this->approveStreamAction->handle($stream);

        // Assert
        $stream = $stream->fresh();
        $this->assertNotNull($stream->approved_at);

        Mail::assertQueued(fn(StreamApprovedMail $mail) => $mail->hasTo($stream->submitted_by_email));
    }

    /** @test */
    public function the_action_calls_the_import_channel_command(): void
    {
        // Arrange
        $stream = Stream::factory()
            ->notApproved()
            ->create();

        Artisan::spy();

        // Act
        $this->approveStreamAction->handle($stream);

        // Assert
        Artisan::shouldHaveReceived('call')->once()->with(ImportChannelsForStreamsCommand::class, ['stream' => $stream]);
    }

    /** @test */
    public function it_will_not_send_a_mail_for_a_link_that_was_already_approved(): void
    {
        $stream = Stream::factory()
            ->approved()
            ->create([
                'submitted_by_email' => 'john@example.com',
            ]);

        $this->approveStreamAction->handle($stream);

        Mail::assertNothingQueued();
    }
}
