<?php

namespace Tests\Feature\Actions\Submission;

use App\Actions\Submission\SubmitStreamAction;
use App\Facades\Youtube;
use App\Mail\StreamSubmittedMail;
use App\Models\Stream;
use App\Services\Youtube\StreamData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SubmitStreamActionTest extends TestCase
{
    use RefreshDatabase;

    private string $youTubeId = '1234';

    /** @test */
    public function it_can_store_a_stream(): void
    {
        // Arrange
        Mail::fake();
        Youtube::partialMock()
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

        $this->assertFalse($stream->isApproved());
        $this->assertEquals('john@example.com', $stream->submitted_by_email);
        $this->assertEquals('de', $stream->language_code);

        Mail::assertQueued(fn(StreamSubmittedMail $mail) => $mail->hasTo('christoph@christoph-rumpel.com'));
    }
}
