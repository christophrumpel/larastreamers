<?php

namespace Tests\Feature;

use App\Models\Stream;
use App\Services\Youtube\StreamData;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArchiveTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_only_finished_streams(): void
    {
    	// Arrange
        Stream::factory()->create(['title' => 'Finished stream', 'status' => StreamData::STATUS_FINISHED]);
        Stream::factory()->create(['title' => 'Live stream', 'status' => StreamData::STATUS_LIVE]);
        Stream::factory()->create(['title' => 'Upcoming stream', 'status' => StreamData::STATUS_UPCOMING]);

    	// Act & Assert
        $this->get(route('archive'))
            ->assertDontSee('Live stream')
            ->assertDontSee('Upcoming stream')
            ->assertSee('Finished stream');
    }

}
