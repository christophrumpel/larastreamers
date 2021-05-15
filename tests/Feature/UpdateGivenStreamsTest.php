<?php

namespace Tests\Feature;

use App\Models\Stream;
use Illuminate\Support\Carbon;
use Tests\Feature\Fakes\YoutubeFake;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateGivenStreamsTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_updates_given_stream_with_new_details(): void
    {
    	// Arrange
        $youtubeFake = (new YoutubeFake(Carbon::tomorrow()))
            ->setTitle('My New Test Stream')
            ->setChannelTitle('My New Channel Name')
            ->setThumbnailUrl('my-new-thumbnail-url');

        $this->app->instance('youtube', $youtubeFake);
        Stream::factory()->create(['title' => 'Stream #1', 'scheduled_start_time' => Carbon::today(), 'youtube_id' => '1234', 'channel_title' => 'My Channel']);

        // Act
        $this->artisan('larastreamers:update-streams');

    	// Assert
        $this->assertDatabaseCount((new Stream)->getTable(), 1);
        $this->assertDatabaseHas((new Stream)->getTable(), [
            'title' => 'My New Test Stream',
            'channel_title' => 'My New Channel Name',
            'thumbnail_url' => 'my-new-thumbnail-url',
            'scheduled_start_time' => Carbon::tomorrow()
        ]);
    }

    /** @test **/
    public function it_tells_if_there_are_no_streams_to_update(): void
    {
        // Act & Expect
        $this->artisan('larastreamers:update-streams')
            ->expectsOutput('There are no streams in the database.')
            ->assertExitCode(0);
    }

    /** @test **/
    public function it_does_not_update_old_streams(): void
    {
        Stream::factory()->create(['scheduled_start_time' => Carbon::yesterday()]);

        // Act & Expect
        $this->artisan('larastreamers:update-streams')
            ->expectsOutput('There are no streams in the database.')
            ->assertExitCode(0);
    }

    /** @test **/
    public function it_tells_how_many_streams_were_updated(): void
    {
        // Arrange
        $youtubeFake = (new YoutubeFake(Carbon::tomorrow()))
            ->setTitle('My New Test Stream')
            ->setChannelTitle('My New Channel Name')
            ->setThumbnailUrl('my-new-thumbnail-url');

        $this->app->instance('youtube', $youtubeFake);
        Stream::factory()->create(['title' => 'My Old Test Stream']);
        Stream::factory()->create(['title' => 'My Old Test Stream']);

        $this->artisan('larastreamers:update-streams')
            ->expectsOutput('2 stream(s) were updated.')
            ->assertExitCode(0);
    }
}
