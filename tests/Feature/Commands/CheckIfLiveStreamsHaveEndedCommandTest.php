<?php

namespace Tests\Feature\Commands;

use App\Console\Commands\CheckIfLiveStreamsHaveEndedCommand;
use App\Models\Stream;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckIfLiveStreamsHaveEndedCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_updates_streams_that_are_currently_live(): void
    {
        // Arrange
        Http::fake();

        // Arrange
        Stream::factory()->live()->create();

        // Act & Expect
        $this->artisan(CheckIfLiveStreamsHaveEndedCommand::class)
            ->expectsOutput('Fetching 1 stream(s) from API to update.')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_does_not_update_finished_or_upcoming_streams(): void
    {
        // Arrange
        Http::fake();

        // Arrange
        Stream::factory()->upcoming()->create();
        Stream::factory()->finished()->create();

        // Act & Expect
        $this->artisan(CheckIfLiveStreamsHaveEndedCommand::class)
            ->expectsOutput('There are no streams to update.')
            ->assertExitCode(0);
    }


}
