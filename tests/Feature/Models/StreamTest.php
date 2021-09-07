<?php

namespace Tests\Feature\Models;

use App\Models\Stream;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StreamTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_only_gives_approved_streams(): void
    {
        // Arrange
        Stream::factory()->notApproved()->create();
        Stream::factory()->approved()->create();

        // Act
        $streams = Stream::approved()->get();

        // Assert
        $this->assertCount(1, $streams);
    }
}
