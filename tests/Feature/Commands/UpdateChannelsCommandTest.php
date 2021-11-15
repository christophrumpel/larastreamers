<?php

namespace Tests\Feature\Commands;

use App\Console\Commands\UpdateChannelsCommand;
use App\Facades\YouTube;
use App\Models\Channel;
use App\Services\YouTube\ChannelData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class UpdateChannelsCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_updates_channels(): void
    {
        // Arrange
        Http::fake();
        YouTube::partialMock()
            ->shouldReceive('channels')
            ->andReturn(collect([
                ChannelData::fake(
                    platformId: '1234',
                    youTubeCustomUrl: 'test',
                    name: 'My new test channel',
                    description: 'My new Description',
                    thumbnailUrl: 'my-new-thumbnail-url',
                    onPlatformSince: Carbon::now()->subYear(2),
                    country: 'US',
                ),
                ChannelData::fake(
                    platformId: '5678',
                    youTubeCustomUrl: 'test',
                    name: 'My new test channel #2',
                    description: 'My new Description #2',
                    thumbnailUrl: 'my-new-thumbnail-url-2',
                    onPlatformSince: Carbon::now()->subYear(2),
                    country: 'DE',
                ),
            ]));

        Channel::factory()->create(['platform_id' => '1234']);
        Channel::factory()->create(['platform_id' => '5678']);

        // Act
        $this->artisan(UpdateChannelsCommand::class)
            ->expectsOutput('Fetching 2 channels(s) from API to update.')
            ->assertExitCode(0);

        // Assert
        Http::assertNothingSent();
        $this->assertDatabaseCount(Channel::class, 2);
        $this->assertDatabaseHas(Channel::class, [
            'name' => 'My new test channel',
            'description' => 'My new Description',
            'thumbnail_url' => 'my-new-thumbnail-url',
            'on_platform_since' => Carbon::now()->subYears(2),
            'country' => 'US',
        ]);
        $this->assertDatabaseHas(Channel::class, [
            'name' => 'My new test channel #2',
            'description' => 'My new Description #2',
            'thumbnail_url' => 'my-new-thumbnail-url-2',
            'country' => 'DE',
        ]);
    }

    /** @test */
    public function it_does_not_update_channels_if_no_channels_given(): void
    {
        // Arrange
        Http::fake();

        // Act & Assert
        $this->artisan(UpdateChannelsCommand::class)
            ->expectsOutput('There are no channels to update.')
            ->assertExitCode(0);
    }
}
