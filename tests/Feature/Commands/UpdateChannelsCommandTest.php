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
            ]));

        Channel::factory()
            ->create([
                'platform_id' => '1234',
                'name' => 'My old channel name',
                'description' => 'My old description',
                'thumbnail_url' => 'my-old-thumbnail-url',
                'on_platform_since' => Carbon::now()->subYears(1),
                'country' => 'AT',
            ]);

        // Act
        $this->artisan(UpdateChannelsCommand::class);

        // Assert
        Http::assertNothingSent();
        $this->assertDatabaseCount(Channel::class, 1);
        $this->assertDatabaseHas(Channel::class, [
            'name' => 'My new test channel',
            'description' => 'My new Description',
            'thumbnail_url' => 'my-new-thumbnail-url',
            'on_platform_since' => Carbon::now()->subYear(2),
            'country' => 'US',
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
