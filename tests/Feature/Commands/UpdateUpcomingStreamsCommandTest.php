<?php

use App\Console\Commands\UpdateUpcomingStreamsCommand;
use App\Facades\YouTube;
use App\Models\Stream;
use App\Services\YouTube\StreamData;
use Illuminate\Support\Carbon;
use Tests\TestCase;


it('updates upcoming streams', function () {
    // Arrange
    YouTube::partialMock()
        ->shouldReceive('videos')
        ->andReturn(collect([
            StreamData::fake(
                videoId: '1234',
                title: 'My New Test Stream',
                description: 'My New Description',
                channelTitle: 'My New Channel Name',
                plannedStart: Carbon::tomorrow()
            ),
        ]));

    Stream::factory()->upcoming()->create(['youtube_id' => '1234']);

    // Act
    $this->artisan(UpdateUpcomingStreamsCommand::class);

    // Assert
    $this->assertDatabaseCount(Stream::class, 1);
    $this->assertDatabaseHas(Stream::class, [
        'title' => 'My New Test Stream',
        'description' => 'My New Description',
        'thumbnail_url' => 'my-new-thumbnail-url',
        'scheduled_start_time' => Carbon::tomorrow(),
    ]);
});

it('does not update finished or live streams', function () {
    // Arrange
    Stream::factory()->finished()->create();
    Stream::factory()->live()->create();

    // Act & Expect
    $this->artisan(UpdateUpcomingStreamsCommand::class)
        ->expectsOutput('There are no streams to update.')
        ->assertExitCode(0);
});

it('does not update unapproved streams', function () {
    // Arrange
    Stream::factory()->notApproved()->create();

    // Act & Expect
    $this->artisan(UpdateUpcomingStreamsCommand::class)
        ->expectsOutput('There are no streams to update.')
        ->assertExitCode(0);
});

it('tells if there are no streams to update', function () {
    $this->assertDatabaseCount(Stream::class, 0);

    // Act & Expect
    $this->artisan(UpdateUpcomingStreamsCommand::class)
        ->expectsOutput('There are no streams to update.')
        ->assertExitCode(0);
});

it('tells how many streams were updated', function () {
    // Arrange
    YouTube::partialMock()
        ->shouldReceive('videos')
        ->andReturn(collect([
            StreamData::fake(videoId: '1'),
            StreamData::fake(videoId: '2'),
        ]));

    Stream::factory()->create(['youtube_id' => '1']);
    Stream::factory()->create(['youtube_id' => '2']);

    $this->artisan(UpdateUpcomingStreamsCommand::class)
        ->expectsOutput('2 stream(s) were updated.')
        ->assertExitCode(0);
});

it('tells how many streams were updated including deletes', function () {
    // Arrange
    YouTube::partialMock()
        ->shouldReceive('videos')
        ->andReturn(collect([
            StreamData::fake(videoId: '1'),
        ]));

    Stream::factory()->create(['youtube_id' => '1']);
    Stream::factory()->create(['youtube_id' => '2']);

    $this->artisan(UpdateUpcomingStreamsCommand::class)
        ->expectsOutput('2 stream(s) were updated.')
        ->assertExitCode(0);
});

it('marks streams as deleted if not given on streaming platform anymore', function () {
    // Arrange
    YouTube::partialMock()
        ->shouldReceive('videos')
        ->andReturn(collect([]));

    $stream = Stream::factory()->create(['youtube_id' => '1']);

    // Act
    $this->artisan(UpdateUpcomingStreamsCommand::class);

    // Assert
    $this->assertDatabaseCount(Stream::class, 1);
    $this->assertDatabaseHas(Stream::class, [
        'title' => $stream->title,
        'status' => StreamData::STATUS_DELETED,
    ]);
});
