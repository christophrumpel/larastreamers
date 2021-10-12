<?php

namespace Tests\Feature\Http\Livewire;

use App\Facades\YouTube;
use App\Http\Livewire\ImportYouTubeLiveStream;
use App\Models\Stream;
use App\Services\YouTube\StreamData;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class ImportYoutubeLiveStreamTest extends TestCase
{
    /** @test */
    public function it_imports_upcoming_stream_from_youTube_url(): void
    {
        // Arrange
        $scheduledStartTime = Carbon::tomorrow();

        YouTube::partialMock()
            ->shouldReceive('videos')
            ->andReturn(collect([
                StreamData::fake(
                    videoId: 'bcnR4NYOw2o',
                    title: 'My Test Stream',
                    description: 'Test Description',
                    channelTitle: 'My Test Channel',
                    thumbnailUrl: 'my-test-thumbnail-url',
                    plannedStart: $scheduledStartTime,
                ),
            ]));

        $this->assertDatabaseCount(Stream::class, 0);

        // Act
        Livewire::test(ImportYouTubeLiveStream::class)
            ->set('youTubeId', 'bcnR4NYOw2o')
            ->call('importStream');

        // Assert
        $this->assertDatabaseHas(Stream::class, [
            'youtube_id' => 'bcnR4NYOw2o',
            'channel_title' => 'My Test Channel',
            'title' => 'My Test Stream',
            'description' => 'Test Description',
            'thumbnail_url' => 'my-test-thumbnail-url',
            'scheduled_start_time' => $scheduledStartTime,
        ]);
    }

    /** @test */
    public function it_does_not_import_streams_which_are_not_upcoming(): void
    {
        // Arrange
        Http::fake();

        // it passes because the video was not found because
        $this->assertDatabaseCount(Stream::class, 0);

        // Arrange & Act & Assert
        Livewire::test(ImportYouTubeLiveStream::class)
            ->set('youTubeId', 'bcnR4NYOw2o')
            ->call('importStream')
            ->assertHasErrors(['stream']);

        $this->assertDatabaseCount(Stream::class, 0);
    }

    /** @test */
    public function it_overrides_if_a_stream_is_already_given(): void
    {
        // Arrange
        YouTube::partialMock()
            ->shouldReceive('videos')
            ->andReturn(collect([
                StreamData::fake(
                    videoId: '1234',
                    title: 'My New Test Stream',
                ),
            ]));

        Stream::factory()->create(['youtube_id' => '1234', 'title' => 'Old title']);

        // Assert
        $this->assertDatabaseCount(Stream::class, 1);

        // Arrange & Act & Assert
        Livewire::test(ImportYouTubeLiveStream::class)
            ->set('youTubeId', '1234')
            ->call('importStream');

        $this->assertDatabaseCount(Stream::class, 1);
        $this->assertDatabaseHas(Stream::class, [
            'youtube_id' => '1234',
            'title' => 'My New Test Stream',
        ]);
    }

    /** @test */
    public function it_shows_success_message(): void
    {
        // Arrange
        YouTube::partialMock()
            ->shouldReceive('videos')
            ->andReturn(collect([
                StreamData::fake(
                    videoId: '1234',
                ),
            ]));

        // Act & Assert
        Livewire::test(ImportYouTubeLiveStream::class)
            ->set('youTubeId', '1234')
            ->call('importStream')
            ->assertSee('Stream "1234" was added successfully.');
    }

    /** @test */
    public function it_clears_form_after_successful_import(): void
    {
        // Arrange
        YouTube::partialMock()
            ->shouldReceive('videos')
            ->andReturn(collect([
                StreamData::fake(
                    videoId: '1234',
                ),
            ]));

        // Act & Assert
        Livewire::test(ImportYouTubeLiveStream::class)
            ->set('youTubeId', '1234')
            ->call('importStream')
            ->assertSet('youTubeId', '');
    }

    /** @test */
    public function it_shows_youTube_client_error_message(): void
    {
        // Arrange
        Http::fake(fn() => Http::response([], 500));

        // Arrange & Act & Assert
        Livewire::test(ImportYouTubeLiveStream::class)
            ->set('youTubeId', '1234')
            ->call('importStream')
            ->assertSee('YouTube API error: 500');
    }

    /** @test */
    public function it_checks_properties_and_method_wired_to_the_view(): void
    {
        // Arrange & Act & Assert
        Livewire::test(ImportYouTubeLiveStream::class)
            ->assertPropertyWired('youTubeId')
            ->assertMethodWiredToForm('importStream');
    }
}
