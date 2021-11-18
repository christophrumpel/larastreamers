<?php

use App\Mail\StreamApprovedMail;
use App\Models\Stream;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\Fakes\YouTubeResponses;
use Tests\TestCase;

uses(TestCase::class);
uses(YouTubeResponses::class);

it('can approve a stream using a signed url', function () {
    // Arrange
    Http::fake(fn() => Http::response($this->videoResponse()));
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
});
