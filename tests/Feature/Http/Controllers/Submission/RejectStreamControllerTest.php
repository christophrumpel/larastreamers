<?php

use App\Models\Stream;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

uses(TestCase::class);

it('can reject a stream using a signed url', function () {
    // Arrange
    Mail::fake();

    $stream = Stream::factory()
        ->notApproved()
        ->create([
            'submitted_by_email' => 'john@example.com',
        ]);

    // Assert
    expect($stream->isApproved())->toBeFalse();

    // Act
    $this->get($stream->rejectUrl())
        ->assertOk();

    // Assert
    expect($stream->refresh()->isApproved())->toBeFalse();
});
