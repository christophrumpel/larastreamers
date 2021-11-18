<?php

use App\Models\Stream;
use Illuminate\Support\Carbon;
use Tests\TestCase;

uses(TestCase::class);

it('only gives approved streams', function () {
    // Arrange
    Stream::factory()->notApproved()->create();
    Stream::factory()->approved()->create();

    // Act
    $streams = Stream::approved()->get();

    // Assert
    $this->assertCount(1, $streams);
});

it('gets next upcoming stream', function () {
    // Arrange
    Stream::factory()
        ->upcoming()
        ->create(['scheduled_start_time' => Carbon::tomorrow()->addDay()]);

    $expectedStream = Stream::factory()
        ->upcoming()
        ->create(['scheduled_start_time' => Carbon::tomorrow()]);

    // Act
    $actualStream = Stream::getNextUpcomingOrLive();

    // Assert
    $this->assertEquals($expectedStream->id, $actualStream->id);
});

it('gets next live stream before upcoming', function () {
    // Arrange
    Stream::factory()
        ->upcoming()
        ->create(['scheduled_start_time' => Carbon::tomorrow()->addDay()]);

    $expectedStream = Stream::factory()
        ->live()
        ->create(['scheduled_start_time' => Carbon::tomorrow()]);

    // Act
    $actualStream = Stream::getNextUpcomingOrLive();

    // Assert
    $this->assertEquals($expectedStream->id, $actualStream->id);
});
