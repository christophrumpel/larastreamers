<?php

namespace Tests\Feature\Actions;

use App\Actions\ImportVideoAction;
use App\Models\Channel;
use Illuminate\Support\Facades\Http;
use Tests\Fakes\YouTubeResponses;
use Tests\TestCase;

class ImportVideoActionTest extends TestCase
{
    use YouTubeResponses;

    /** @test */
    public function it_adds_a_channel_id_if_channel_already_given(): void
    {
        Http::fake([
            '*videos*' => Http::response($this->singleVideoResponse()),
        ]);

        // Arrange
        $youTubeId = 'gzqJZQyfkaI';
        $channel = Channel::factory()->create(['platform_id' => 'UCNlUCA4VORBx8X-h-rXvXEg']);

        // Act
        $importedStream = (new ImportVideoAction())->handle($youTubeId);

        // Assert
        $this->assertEquals($channel->id, $importedStream->channel_id);
    }
}
