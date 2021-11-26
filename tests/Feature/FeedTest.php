<?php

use App\Models\Stream;
use Carbon\Carbon;
use Tests\TestCase;


it('provides streams in rss feed', function () {
    // Arrange
    Stream::factory()->create([
        'title' => 'Stream tomorrow',
        'description' => 'Stream description',
        'scheduled_start_time' => Carbon::tomorrow(),
    ]);
    Stream::factory()->create(['title' => 'Stream today', 'scheduled_start_time' => Carbon::today()]);
    Stream::factory()->create(['title' => 'Stream yesterday', 'scheduled_start_time' => Carbon::yesterday()]);

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
        'Stream yesterday',
    ]);
});
