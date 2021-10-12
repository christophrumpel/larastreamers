<?php

namespace Tests\Feature;

use App\Models\Channel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PageStreamersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_channel_data(): void
    {
    	// Arrange
        $channel = Channel::factory()
            ->create(['name' => 'Channel Dries']);

        // Act
        $response = $this->get(route('streamers'));

    	// Assert
        $response->assertSee([
            $channel->name,
            $channel->description,
            $channel->thumbnail_url,
            route('archive', ['search' => $channel->name])
        ]);
    }

    /** @test */
    public function it_shows_all_streamers_alphabetically(): void
    {
    	// Arrange
        Channel::factory()
            ->create(['name' => 'C Channel Dries']);
        Channel::factory()
            ->create(['name' => 'A Channel Mohamed']);
        Channel::factory()
            ->create(['name' => 'B Channel Steve']);

    	// Act
        $response = $this->get(route('streamers'));

    	// Assert
        $response->assertSeeInOrder([
            'A Channel Mohamed',
            'B Channel Steve',
            'C Channel Dries'
        ]);
    }
}
