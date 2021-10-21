<?php

namespace Tests\Feature\Http\Controllers\Submission;

use App\Mail\StreamApprovedMail;
use App\Models\Stream;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ApproveStreamControllerTest extends TestCase
{
    /** @test */
    public function it_can_approve_a_stream_using_a_signed_url(): void
    {
        // Arrange
        Mail::fake();
        $stream = Stream::factory()
            ->notApproved()
            ->create([
                'submitted_by_email' => 'john@example.com',
            ]);

        Artisan::spy();

        // Assert
        $this->assertFalse($stream->isApproved());

        // Act
        $this->get($stream->approveUrl())
            ->assertOk();

        // Assert
        $this->assertTrue($stream->refresh()->isApproved());

        Mail::assertQueued(StreamApprovedMail::class);
    }
}
