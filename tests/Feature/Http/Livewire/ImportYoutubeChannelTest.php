<?php

use App\Http\Livewire\ImportYouTubeChannel;
use App\Jobs\ImportYoutubeChannelStreamsJob;
use App\Models\Channel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\Fakes\YouTubeResponses;
use Tests\TestCase;

uses(YouTubeResponses::class);

it('adds channel to database', function () {
    // Arrange
    Queue::fake();

    // Assert
    $this->assertDatabaseCount(Channel::class, 0);

    // Arrange & Act
    Http::fake(fn() => Http::response($this->channelResponse()));

    Livewire::test(ImportYouTubeChannel::class)
        ->set('youTubeChannelId', 'UCdtd5QYBx9MUVXHm7qgEpxA')
        ->call('importChannel');

    // Assert
    $this->assertDatabaseCount(Channel::class, 1);
    $this->assertDatabaseHas(Channel::class, [
        'platform' => 'youtube',
        'platform_id' => 'UCdtd5QYBx9MUVXHm7qgEpxA',
    ]);
});

it('dispatches job to import upcoming streams', function () {
    // Arrange
    Queue::fake();
    Http::fake(fn() => Http::response($this->channelResponse()));

    // Act
    Livewire::test(ImportYouTubeChannel::class)
        ->set('youTubeChannelId', 'UCdtd5QYBx9MUVXHm7qgEpxA')
        ->call('importChannel');

    // Assert
    Queue::assertPushed(ImportYoutubeChannelStreamsJob::class);
});

it('shows success message', function () {
    // Arrange
    Queue::fake();
    Http::fake(fn() => Http::response($this->channelResponse()));

    // Act & Assert
    Livewire::test(ImportYouTubeChannel::class)
        ->set('youTubeChannelId', 'UCdtd5QYBx9MUVXHm7qgEpxA')
        ->call('importChannel')
        ->assertSee('Channel "UCdtd5QYBx9MUVXHm7qgEpxA" was added successfully.');
});

it('shows youtube client error message', function () {
    // Arrange
    Http::fake(fn() => Http::response([], 500));

    // Arrange & Act & Assert
    Livewire::test(ImportYouTubeChannel::class)
        ->set('youTubeChannelId', 'UCdtd5QYBx9MUVXHm7qgEpxA')
        ->call('importChannel')
        ->assertSee('YouTube API error: 500');
});

it('clears form after successful import', function () {
    // Arrange
    Queue::fake();
    Http::fake(fn() => Http::response($this->channelResponse()));

    // Act & Assert
    Livewire::test(ImportYouTubeChannel::class)
        ->set('youTubeChannelId', 'UCdtd5QYBx9MUVXHm7qgEpxA')
        ->call('importChannel')
        ->assertSet('youTubeChannelId', '');
});

it('wires properties and methods', function () {
    // Arrange & Act & Assert
    Livewire::test(ImportYouTubeChannel::class)
        ->assertPropertyWired('youTubeChannelId')
        ->assertMethodWiredToForm('importChannel');
});
