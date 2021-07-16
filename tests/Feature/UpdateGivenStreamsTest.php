<?php

namespace Tests\Feature;

use App\Console\Commands\UpdateGivenStreams;
use App\Facades\Youtube;
use App\Models\Stream;
use App\Services\Youtube\StreamData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class UpdateGivenStreamsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_updates_given_stream_with_new_details(): void
    {
        // Arrange
        Youtube::partialMock()
            ->shouldReceive('videos')
            ->andReturn(collect([
                StreamData::fake(videoId: '1234', title: 'My New Test Stream', description: 'My New Description', channelTitle: 'My New Channel Name', plannedStart: Carbon::tomorrow()),
            ]));

        Stream::factory()->create(['youtube_id' => '1234']);

        // Act
        $this->artisan(UpdateGivenStreams::class);

        // Assert
        $this->assertDatabaseCount(Stream::class, 1);
        $this->assertDatabaseHas(Stream::class, [
            'title' => 'My New Test Stream',
            'description' => 'My New Description',
            'channel_title' => 'My New Channel Name',
            'thumbnail_url' => 'my-new-thumbnail-url',
            'scheduled_start_time' => Carbon::tomorrow(),
        ]);
    }

    /** @test */
    public function it_tells_if_there_are_no_streams_to_update(): void
    {
        // Act & Expect
        $this->artisan(UpdateGivenStreams::class)
            ->expectsOutput('There are no streams to update.')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_tells_how_many_streams_were_updated(): void
    {
        // Arrange
        Youtube::partialMock()
            ->shouldReceive('videos')
            ->andReturn(collect([
                StreamData::fake(videoId: '1'),
                StreamData::fake(videoId: '2'),
            ]));

        Stream::factory()->create(['youtube_id' => '1']);
        Stream::factory()->create(['youtube_id' => '2']);

        $this->artisan(UpdateGivenStreams::class)
            ->expectsOutput('2 stream(s) were updated.')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_updates_streams_that_are_live_when_soon_live_option_given(): void
    {
        // Arrange
        Http::fake();

        // Arrange
        Stream::factory()->create(['status' => StreamData::STATUS_UPCOMING]);
        Stream::factory()->create(['status' => StreamData::STATUS_LIVE]);
        Stream::factory()->create(['status' => StreamData::STATUS_FINISHED]);

        // Act & Expect
        $this->artisan('larastreamers:update-streams --soon-live-only')
             ->expectsOutput('Fetching 1 stream(s) from API.')
             ->assertExitCode(0);
    }

    /** @test */
    public function it_updates_streams_that_are_live_soon_when_soon_live_option_given(): void
    {
        // Arrange
        Http::fake();

        // Arrange
        Stream::factory()->upcoming()->create(['scheduled_start_time' => now()->addMinutes(15)]);
        Stream::factory()->upcoming()->create(['scheduled_start_time' => now()->addMinutes(20)]);
        Stream::factory()->finished()->create();

        // Act & Expect
        $this->artisan('larastreamers:update-streams --soon-live-only')
            ->expectsOutput('Fetching 1 stream(s) from API.')
            ->assertExitCode(0);
    }

}
