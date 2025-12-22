<?php

use App\Actions\Submission\ApproveStreamAction;
use App\Actions\Submission\RejectStreamAction;
use App\Jobs\ImportYoutubeChannelStreamsJob;
use App\Models\Channel;
use App\Models\Stream;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\Fakes\YouTubeResponses;

uses(YouTubeResponses::class);

it('prevents approval of rejected streams', function() {
    // Arrange
    Http::fake(fn () => Http::response($this->videoResponse()));
    Mail::fake();
    
    $stream = Stream::factory()
        ->notApproved()
        ->create([
            'submitted_by_email' => 'john@example.com',
            'rejected_at' => now(),
        ]);

    // Act
    $action = app(ApproveStreamAction::class);
    $action->handle($stream);

    // Assert - Stream should not be approved
    expect($stream->refresh()->isApproved())->toBeFalse();
    expect($stream->isRejected())->toBeTrue();
});

it('prevents rejection of already approved streams', function() {
    // Arrange
    Mail::fake();
    
    $stream = Stream::factory()
        ->approved()
        ->create([
            'submitted_by_email' => 'john@example.com',
        ]);

    $approvedAt = $stream->approved_at;

    // Act
    $action = app(RejectStreamAction::class);
    $action->handle($stream);

    // Assert - Stream should remain approved and not be rejected
    expect($stream->refresh()->isApproved())->toBeTrue();
    expect($stream->approved_at)->toEqual($approvedAt);
    expect($stream->isRejected())->toBeFalse();
});

it('marks stream as rejected when rejection action is called', function() {
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
    expect($stream->refresh()->isRejected())->toBeTrue();
    expect($stream->rejected_at)->not->toBeNull();
});

it('does not auto-import rejected streams via job', function() {
    // Arrange
    $channel = Channel::factory()->create([
        'platform_id' => 'test-channel-id',
    ]);

    // Create a rejected stream
    $rejectedStream = Stream::factory()->create([
        'youtube_id' => 'rejected-video',
        'rejected_at' => now(),
        'approved_at' => null,
    ]);

    // Mock YouTube API response
    \App\Facades\YouTube::partialMock()
        ->shouldReceive('upcomingStreams')
        ->with('test-channel-id')
        ->andReturn(collect([
            \App\Services\YouTube\StreamData::fake(
                videoId: 'rejected-video',
                channelId: 'test-channel-id',
            ),
        ]));

    // Act
    $job = new ImportYoutubeChannelStreamsJob('test-channel-id', 'en');
    $job->handle();

    // Assert - Stream should still be rejected and not approved
    expect($rejectedStream->refresh()->isRejected())->toBeTrue();
    expect($rejectedStream->isApproved())->toBeFalse();
});

it('prevents double approval via repeated API calls', function() {
    // Arrange
    Http::fake(fn () => Http::response($this->videoResponse()));
    Mail::fake();
    
    $stream = Stream::factory()
        ->notApproved()
        ->create([
            'submitted_by_email' => 'john@example.com',
        ]);

    // Act - Call approve twice
    $action = app(ApproveStreamAction::class);
    $action->handle($stream);
    $firstApprovalTime = $stream->refresh()->approved_at;
    
    sleep(1);
    $action->handle($stream);
    $secondApprovalTime = $stream->refresh()->approved_at;

    // Assert - Approval time should be the same (not updated on second call)
    expect($firstApprovalTime->equalTo($secondApprovalTime))->toBeTrue();
    Mail::assertQueuedCount(1); // Only one email should be sent
});
