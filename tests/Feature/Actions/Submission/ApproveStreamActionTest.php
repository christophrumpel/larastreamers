<?php

use App\Actions\Submission\ApproveStreamAction;
use App\Console\Commands\ImportChannelsForStreamsCommand;
use App\Mail\StreamApprovedMail;
use App\Models\Stream;
use App\Services\YouTube\StreamData;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\Fakes\YouTubeResponses;

uses(YouTubeResponses::class);

beforeEach(function() {
    Mail::fake();
    Http::fake(fn() => Http::response($this->videoResponse()));
    $this->approveStreamAction = app(ApproveStreamAction::class);
});

test('the action can approve a stream', function() {
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
});

test('the action calls the import channel command', function() {
    // Arrange
    $stream = Stream::factory()
        ->notApproved()
        ->create();

    Artisan::spy();

    // Act
    $this->approveStreamAction->handle($stream);

    // Assert
    Artisan::shouldHaveReceived('call')->once()->with(ImportChannelsForStreamsCommand::class, ['stream' => $stream]);
});

test('the action calls does not import a channel if channel already given', function() {
    // Arrange
    $stream = Stream::factory()
        ->withChannel()
        ->notApproved()
        ->create();

    Artisan::spy();

    // Assert
    Artisan::shouldNotReceive('call')->with(ImportChannelsForStreamsCommand::class, ['stream' => $stream]);

    // Act
    $this->approveStreamAction->handle($stream);
});

it('will not send a mail for a link that was already approved', function() {
    // Arrange
    $stream = Stream::factory()
        ->approved()
        ->create([
            'submitted_by_email' => 'john@example.com',
        ]);

    // Act
    $this->approveStreamAction->handle($stream);

    // Assert
    Mail::assertNothingQueued();
});

it('updates stream before approving it', function() {
    // Arrange
    $stream = Stream::factory()
        ->upcoming()
        ->notApproved()
        ->create([
            'submitted_by_email' => 'john@example.com',
        ]);
    Artisan::spy();

    // Act
    $this->approveStreamAction->handle($stream);

    // Assert
    $stream->refresh();
    expect($stream->status)->toEqual(StreamData::STATUS_FINISHED);
});
