<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\ImportYoutubeLiveStream;
use App\Models\Stream;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Livewire\Livewire;
use Tests\Feature\Fakes\YoutubeFake;
use Tests\TestCase;

class ImportYoutubeLiveStreamTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_imports_upcoming_stream_from_youtube_url(): void
    {
        // Arrange
        $scheduledStartTime = Carbon::now()->addDay();
        $youtubeFake = (new YoutubeFake($scheduledStartTime))
            ->setTitle('My Test Stream')
            ->setChannelTitle('My Test Channel')
            ->setThumbnailUrl('my-test-thumbnail-url');

        $this->app->instance('youtube', $youtubeFake);
        $this->assertDatabaseCount((new Stream())->getTable(), 0);

        // Act
        Livewire::test(ImportYoutubeLiveStream::class)
            ->set('youtubeId', 'bcnR4NYOw2o')
            ->call('importStream');

        // Assert
        $this->assertDatabaseHas((new Stream)->getTable(), [
            'channel_title' => 'My Test Channel',
            'title' => 'My Test Stream',
            'thumbnail_url' => 'my-test-thumbnail-url',
            'scheduled_start_time' => $scheduledStartTime->timezone('Europe/Vienna')->format('Y-m-d H:i:s')
        ]);
    }

    /** @test */
    public function it_does_not_import_streams_which_are_not_upcoming(): void
    {
        // Arrange
        $this->app->instance('youtube', (new YoutubeFake)->setUpcomingFalse());
        $this->assertDatabaseCount((new Stream())->getTable(), 0);

    	// Arrange & Act & Assert
        Livewire::test(ImportYoutubeLiveStream::class)
            ->set('youtubeId', 'bcnR4NYOw2o')
            ->call('importStream')
            ->assertHasErrors(['stream']);

        $this->assertDatabaseCount((new Stream())->getTable(), 0);
    }

    /** @test */
    public function it_overrides_if_a_stream_is_already_given(): void
    {
        // Arrange
        Stream::factory()->create(['youtube_id' => '1234', 'title' => 'Old title']);
        $this->app->instance('youtube', (new YoutubeFake)->setYoutubeId('1234'));
        $this->assertDatabaseCount((new Stream())->getTable(), 1);

        // Arrange & Act & Assert
        Livewire::test(ImportYoutubeLiveStream::class)
            ->set('youtubeId', '1234')
            ->call('importStream');

        $this->assertDatabaseCount((new Stream())->getTable(), 1);
    }

    /** @test */
    public function it_checks_properties_and_method_wired_to_the_view(): void
    {
    	// Arrange & Act & Assert
        Livewire::test(ImportYoutubeLiveStream::class)
            ->assertPropertyWired('youtubeId')
            ->assertMethodWiredToForm('importStream');
    }
}
