<?php

namespace Tests\Feature\Http\Controllers\Submission;

use App\Models\Stream;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RejectStreamControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_reject_a_stream_using_a_signed_url()
    {
        // Arrange
        Mail::fake();

        $stream = Stream::factory()
            ->notApproved()
            ->create([
                'submitted_by_email' => 'john@example.com',
            ]);

        // Assert
        $this->assertFalse($stream->isApproved());

        // Act
        $this->get($stream->rejectUrl())
            ->assertOk();

        // Assert
        $this->assertFalse($stream->refresh()->isApproved());
    }
}
