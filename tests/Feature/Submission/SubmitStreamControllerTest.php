<?php

namespace Tests\Feature\Submission;

use App\Http\Controllers\Submission\SubmitStreamController;
use App\Mail\StreamSubmittedMail;
use App\Models\Stream;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use function action;

class SubmitStreamControllerTest extends TestCase
{
    use RefreshDatabase;

    protected string $youTubeId;

    public function setUp(): void
    {
        parent::setUp();

        Mail::fake();

        Http::fake([
            '*video*' => Http::response($this->videoResponse()),
        ]);

        $this->youTubeId = 'gzqJZQyfkaI';
    }

    /** @test */
    public function it_can_store_a_stream()
    {
        $submittedByEmail = 'john@example.com';

        $url = action(SubmitStreamController::class);

        $response = $this->post($url, [
            'youtube_id' => $this->youTubeId,
            'email' => $submittedByEmail,
        ]);

        $response->assertSessionHasNoErrors();

        $stream = Stream::firstWhere('youtube_id', $this->youTubeId);
        $this->assertNotNull($stream);

        $this->assertFalse($stream->isApproved());
        $this->assertEquals($submittedByEmail, $stream->submitted_by_email);

        Mail::assertQueued(fn(StreamSubmittedMail $mail) =>
            $mail->hasTo('christoph@christoph-rumpel.com')
        );
    }

    /** @test */
    public function it_will_not_accept_a_video_that_is_already_in_the_db()
    {
        Stream::factory()->create([
            'youtube_id' => $this->youTubeId,
        ]);

        $response = $this->post(action(SubmitStreamController::class), [
            'youtube_id' => $this->youTubeId,
            'email' => 'john@example.com',
        ]);

        $response->assertSessionHasErrors(['youtube_id']);

        $this->assertCount(1, Stream::all());

        Mail::assertNothingQueued();
    }
}
