<?php

namespace Tests\Feature\Http\Controllers\Submission;

use App\Mail\StreamApprovedMail;
use App\Models\Stream;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ApproveStreamControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_approve_a_stream_using_a_signed_url()
    {
        Mail::fake();

        $stream = Stream::factory()->create([
            'submitted_by_email' => 'john@example.com',
            'approved_at' => null,
        ]);

        $this->assertFalse($stream->isApproved());

        $this->get($stream->approveUrl())->assertStatus(Response::HTTP_OK);

        $this->assertTrue($stream->refresh()->isApproved());

        Mail::assertQueued(StreamApprovedMail::class);
    }
}
