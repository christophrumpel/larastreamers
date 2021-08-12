<?php

namespace Tests\Feature\Commands;

use App\Console\Commands\UpdateUpcomingStreamsCommand;
use App\Facades\Youtube;
use App\Models\Stream;
use App\Services\Youtube\StreamData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class UpdateUpcomingStreamsCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_updates_upcoming_streams(): void
    {
        // Arrange
        Youtube::partialMock()
            ->shouldReceive('videos')
            ->andReturn(collect([
                StreamData::fake(
                    videoId: '1234',
                    title: 'My New Test Stream',
                    description: 'My New Description',
                    channelTitle: 'My New Channel Name',
                    plannedStart: Carbon::tomorrow()
                ),
            ]));

        Stream::factory()->upcoming()->create(['youtube_id' => '1234']);

        // Act
        $this->artisan(UpdateUpcomingStreamsCommand::class);

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
    public function it_does_not_update_finished_or_live_streams(): void
    {
        // Arrange
        Stream::factory()->finished()->create();
        Stream::factory()->live()->create();

        // Act & Expect
        $this->artisan(UpdateUpcomingStreamsCommand::class)
            ->expectsOutput('There are no streams to update.')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_does_not_update_unapproved_streams(): void
    {
        // Arrange
        Stream::factory()->notApproved()->create();

        // Act & Expect
        $this->artisan(UpdateUpcomingStreamsCommand::class)
            ->expectsOutput('There are no streams to update.')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_tells_if_there_are_no_streams_to_update(): void
    {
        // Act & Expect
        $this->artisan(UpdateUpcomingStreamsCommand::class)
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

        $this->artisan(UpdateUpcomingStreamsCommand::class)
            ->expectsOutput('2 stream(s) were updated.')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_tells_how_many_streams_were_updated_including_deletes(): void
    {
        // Arrange
        Youtube::partialMock()
            ->shouldReceive('videos')
            ->andReturn(collect([
                StreamData::fake(videoId: '1'),
            ]));

        Stream::factory()->create(['youtube_id' => '1']);
        Stream::factory()->create(['youtube_id' => '2']);

        $this->artisan(UpdateUpcomingStreamsCommand::class)
            ->expectsOutput('2 stream(s) were updated.')
            ->assertExitCode(0);
    }


    /** @test */
    public function it_marks_streams_as_deleted_if_not_given_on_streaming_platform_anymore(): void
    {
        // Arrange
        Youtube::partialMock()
            ->shouldReceive('videos')
            ->andReturn(collect([]));

        $stream = Stream::factory()->create(['youtube_id' => '1']);

        // Act
        $this->artisan(UpdateUpcomingStreamsCommand::class);

        // Assert
        $this->assertDatabaseCount(Stream::class, 1);
        $this->assertDatabaseHas(Stream::class, [
            'title' => $stream->title,
            'status' => StreamData::STATUS_DELETED,
        ]);
    }
}
