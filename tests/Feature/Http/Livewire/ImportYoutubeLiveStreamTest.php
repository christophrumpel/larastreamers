<?php

use App\Facades\YouTube;
use App\Http\Livewire\ImportYouTubeLiveStream;
use App\Models\Stream;
use App\Services\YouTube\StreamData;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

it('imports upcoming stream from you tube url', function() {
    // Arrange
    $scheduledStartTime = Carbon::tomorrow();

    YouTube::partialMock()
        ->shouldReceive('videos')
        ->andReturn(collect([
            StreamData::fake(
                videoId: 'bcnR4NYOw2o',
                title: 'My Test Stream',
                description: 'Test Description',
                channelTitle: 'My Test Channel',
                thumbnailUrl: 'my-test-thumbnail-url',
                plannedStart: $scheduledStartTime,
            ),
        ]));

    $this->assertDatabaseCount(Stream::class, 0);

    // Act
    Livewire::test(ImportYouTubeLiveStream::class)
        ->set('youTubeId', 'bcnR4NYOw2o')
        ->call('importStream');

    // Assert
    $this->assertDatabaseHas(Stream::class, [
        'youtube_id' => 'bcnR4NYOw2o',
        'title' => 'My Test Stream',
        'description' => 'Test Description',
        'thumbnail_url' => 'my-test-thumbnail-url',
        'scheduled_start_time' => $scheduledStartTime,
    ]);
});

it('does not import streams which are not upcoming', function() {
    // Arrange
    Http::fake();

    // it passes because the video was not found because
    $this->assertDatabaseCount(Stream::class, 0);

    // Arrange & Act & Assert
    Livewire::test(ImportYouTubeLiveStream::class)
        ->set('youTubeId', 'bcnR4NYOw2o')
        ->call('importStream')
        ->assertHasErrors(['stream']);

    $this->assertDatabaseCount(Stream::class, 0);
});

it('overrides if a stream is already given', function() {
    // Arrange
    YouTube::partialMock()
        ->shouldReceive('videos')
        ->andReturn(collect([
            StreamData::fake(
                videoId: '1234',
                title: 'My New Test Stream',
            ),
        ]));

    Stream::factory()->create(['youtube_id' => '1234', 'title' => 'Old title']);

    // Assert
    $this->assertDatabaseCount(Stream::class, 1);

    // Arrange & Act & Assert
    Livewire::test(ImportYouTubeLiveStream::class)
        ->set('youTubeId', '1234')
        ->call('importStream');

    $this->assertDatabaseCount(Stream::class, 1);
    $this->assertDatabaseHas(Stream::class, [
        'youtube_id' => '1234',
        'title' => 'My New Test Stream',
    ]);
});

it('shows success message', function() {
    // Arrange
    YouTube::partialMock()
        ->shouldReceive('videos')
        ->andReturn(collect([
            StreamData::fake(
                videoId: '1234',
            ),
        ]));

    // Act & Assert
    Livewire::test(ImportYouTubeLiveStream::class)
        ->set('youTubeId', '1234')
        ->call('importStream')
        ->assertSee('Stream "1234" was added successfully.');
});

it('clears form after successful import', function() {
    // Arrange
    YouTube::partialMock()
        ->shouldReceive('videos')
        ->andReturn(collect([
            StreamData::fake(
                videoId: '1234',
            ),
        ]));

    // Act & Assert
    Livewire::test(ImportYouTubeLiveStream::class)
        ->set('youTubeId', '1234')
        ->call('importStream')
        ->assertSet('youTubeId', '');
});

it('shows you tube client error message', function() {
    // Arrange
    Http::fake(fn() => Http::response([], 500));

    // Arrange & Act & Assert
    Livewire::test(ImportYouTubeLiveStream::class)
        ->set('youTubeId', '1234')
        ->call('importStream')
        ->assertSee('YouTube API error: 500');
});

it('checks properties and method wired to the view', function() {
    // Arrange & Act & Assert
    Livewire::test(ImportYouTubeLiveStream::class)
        ->assertPropertyWired('youTubeId')
        ->assertMethodWiredToForm('importStream');
});
