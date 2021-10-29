<?php

namespace Tests\Feature\Commands;

use App\Console\Commands\UpdateAllChannelDetails;
use App\Facades\YouTube;
use App\Models\Channel;
use App\Services\YouTube\ChannelData;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class UpdateAllChannelDetailsCommandTest extends TestCase
{
    /** @test */
    public function it_update_channels(): void
    {
        // Arrange
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

        Channel::factory()->create(['platform_id' => '1234']);

        // Act
        $this->artisan(UpdateAllChannelDetails::class);

        // Assert
        $this->assertDatabaseCount(Channel::class, 1);
        $this->assertDatabaseHas(Channel::class, [
            'name' => 'My new test channel',
            'description' => 'My new Description',
            'thumbnail_url' => 'my-new-thumbnail-url',
            'on_platform_since' => Carbon::now()->subYear(2),
            'country' => 'US',
        ]);
    }
}
