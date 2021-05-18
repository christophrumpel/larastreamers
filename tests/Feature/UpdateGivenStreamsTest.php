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
                StreamData::fake(videoId: '1234', title: 'My New Test Stream', channelTitle: 'My New Channel Name', plannedStart: Carbon::tomorrow()),
            ]));

        Stream::factory()->create(['title' => 'Stream #1', 'scheduled_start_time' => Carbon::today(), 'youtube_id' => '1234', 'channel_title' => 'My Channel']);

        // Act
        $this->artisan(UpdateGivenStreams::class);

        // Assert
        $this->assertDatabaseCount((new Stream)->getTable(), 1);
        $this->assertDatabaseHas((new Stream)->getTable(), [
            'title' => 'My New Test Stream',
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
    public function it_only_update_specific_streams_when_frequent_option_is_given(): void
    {
        // Arrange
        Http::fake();

        // Arrange
        Stream::factory()->create(['youtube_id' => 'ended', 'status' => StreamData::STATUS_NONE]);
        Stream::factory()->create(['youtube_id' => 'live', 'status' => StreamData::STATUS_LIVE]);
        Stream::factory()->create(['youtube_id' => 'soon', 'status' => StreamData::STATUS_UPCOMING, 'scheduled_start_time' => now()->addMinutes(9)]);
        Stream::factory()->create(['youtube_id' => 'tomorrow', 'status' => StreamData::STATUS_UPCOMING, 'scheduled_start_time' => now()->addDay()]);

        // Act & Expect
        $this->artisan('larastreamers:update-streams --frequent')
             ->expectsOutput('Fetching 2 stream(s) from API.')
             ->assertExitCode(0);

        $this->artisan(UpdateGivenStreams::class)
            ->expectsOutput('Fetching 3 stream(s) from API.')
             ->assertExitCode(0);
    }
}
