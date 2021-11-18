<?php

use App\Console\Commands\ImportChannelsForStreamsCommand;
use App\Models\Channel;
use App\Models\Stream;
use Illuminate\Support\Facades\Http;
use Tests\Fakes\YouTubeResponses;
use Tests\TestCase;

uses(TestCase::class);
uses(YouTubeResponses::class);

it('imports channel for stream', function () {
    Http::fake([
        '*videos*' => Http::response($this->videoResponse()),
        '*channels*' => Http::response($this->channelResponse()),
    ]);

    // Arrange
    $stream1 = Stream::factory()
        ->create(['youtube_id' => 'gzqJZQyfkaI']);
    Stream::factory()
        ->create(['youtube_id' => 'bcnR4NYOw2o']);
    Stream::factory()
        ->create(['youtube_id' => 'L3O1BbybSgw']);

    // Act
    $this->artisan(ImportChannelsForStreamsCommand::class)
        ->expectsOutput('Fetching 3 stream(s) from API to check for channel.');

    // Assert
    $this->assertDatabaseHas(Channel::class, [
        'platform_id' => 'UCdtd5QYBx9MUVXHm7qgEpxA',
    ]);

    $stream1->refresh();
    expect($stream1->channel_id)->toEqual(1);
});

it('imports channel for specific stream', function () {
    Http::fake([
        '*videos*' => Http::response($this->singleVideoResponse()),
        '*channels*' => Http::response($this->channelResponse()),
    ]);

    // Arrange
    $streamWithoutChannelToImport = Stream::factory()
        ->create(['youtube_id' => 'gzqJZQyfkaI']);
    Stream::factory()
        ->create(['youtube_id' => 'bcnR4NYOw2o']);

    // Act
    $this->artisan(ImportChannelsForStreamsCommand::class, ['stream' => $streamWithoutChannelToImport])
        ->expectsOutput('Fetching 1 stream(s) from API to check for channel.');

    // Assert
    $this->assertDatabaseHas(Channel::class, [
        'platform_id' => 'UCdtd5QYBx9MUVXHm7qgEpxA',
    ]);
});

it('does not import channel for pending stream', function () {
    Http::fake();

    // Arrange
    Stream::factory()
        ->notApproved()
        ->create(['youtube_id' => 'gzqJZQyfkaI']);

    // Act & Assert
    $this->artisan(ImportChannelsForStreamsCommand::class)
        ->expectsOutput('There are no streams without a channel.')
        ->assertExitCode(0);

    Http::assertNothingSent();
});

it('updates channel if already given', function () {
    Http::fake([
        '*videos*' => Http::response($this->singleVideoResponse()),
        '*channels*' => Http::response($this->channelResponse()),
    ]);

    // Arrange
    Channel::factory()
        ->create(['platform_id' => 'UCdtd5QYBx9MUVXHm7qgEpxA']);
    Stream::factory()
        ->approved()
        ->create(['channel_id' => null, 'youtube_id' => 'gzqJZQyfkaI']);

    // Act & Assert
    $this->artisan(ImportChannelsForStreamsCommand::class);

    $this->assertDatabaseCount(Channel::class, 1);
});

it('does not call youtube if all channels given', function () {
    // Arrange
    Http::fake();
    Stream::factory()
        ->for(Channel::factory())
        ->create();

    // Act
    $this->artisan(ImportChannelsForStreamsCommand::class)
        ->expectsOutput('There are no streams without a channel.')
        ->assertExitCode(0);

    // Assert
    Http::assertNothingSent();
});
