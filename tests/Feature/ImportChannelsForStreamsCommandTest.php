<?php

namespace Tests\Feature;

use App\Console\Commands\ImportChannelsForStreamsCommand;
use App\Models\Channel;
use App\Models\Stream;
use Illuminate\Support\Facades\Http;
use Tests\Fakes\YouTubeResponses;
use Tests\TestCase;

class ImportChannelsForStreamsCommandTest extends TestCase
{
    use YouTubeResponses;

    /** @test */
    public function it_imports_channel_for_stream(): void
    {
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
        $this->assertEquals(1, $stream1->channel_id);
    }

    /** @test */
    public function it_imports_channel_for_specific_stream(): void
    {
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
    }


    /** @test */
    public function it_does_not_import_channel_for_pending_stream(): void
    {
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
    }

    /** @test */
    public function it_updates_channel_if_already_given(): void
    {
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
    }

    /** @test */
    public function it_does_not_call_youtube_if_all_channels_given(): void
    {
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
    }
}
