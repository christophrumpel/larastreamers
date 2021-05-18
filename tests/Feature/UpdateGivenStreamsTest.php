<?php

namespace Tests\Feature;

use App\Console\Commands\UpdateGivenStreams;
use App\Facades\Youtube;
use App\Jobs\TweetStreamIsLiveJob;
use App\Models\Stream;
use App\Services\Youtube\StreamData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
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
            ->expectsOutput('There are no streams in the database.')
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
}
