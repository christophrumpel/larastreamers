<?php

use App\Models\Stream;
use Carbon\Carbon;

it('provides streams in rss feed', function() {
    // Arrange
    Stream::factory()->create([
        'title' => 'Stream tomorrow',
        'description' => 'Stream description',
        'scheduled_start_time' => Carbon::tomorrow(),
    ]);
    Stream::factory()->create(['title' => 'Stream today', 'scheduled_start_time' => Carbon::now()->addHour(1)]);

    // Act
    $response = $this->get('feed');

    // Assert
    $response->assertSeeInOrder([
        'Stream tomorrow',
        'Stream description',
    ]);

    $response->assertSeeInOrder([
        'Stream tomorrow',
        'Stream today',
    ]);
});
