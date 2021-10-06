<?php

namespace Tests\Feature\Commands;

use App\Console\Commands\CheckIfUpcomingStreamsAreLiveCommand;
use App\Models\Stream;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CheckIfUpcomingStreamsAreLiveCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_updates_upcoming_streams_that_are_soon_live(): void
    {
        // Arrange
        Http::fake();

        // Arrange
        Stream::factory()->upcoming()->create(['scheduled_start_time' => now()->addMinutes(15)]);
        Stream::factory()->upcoming()->create(['scheduled_start_time' => now()->addMinutes(20)]);

        // Act & Expect
        $this->artisan(CheckIfUpcomingStreamsAreLiveCommand::class)
            ->expectsOutput('Fetching 1 stream(s) from API to update their status.')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_does_not_update_finished_or_live_streams(): void
    {
        // Arrange
        Http::fake();
        Stream::factory()->live()->create();
        Stream::factory()->finished()->create();

        // Act & Expect
        $this->artisan(CheckIfUpcomingStreamsAreLiveCommand::class)
            ->expectsOutput('There are no streams to update.')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_does_not_update_unapproved_streams(): void
    {
        // Arrange
        Http::fake();

        // Arrange
        Stream::factory()->notApproved()->create(['scheduled_start_time' => now()->addMinutes(15)]);

        // Act & Expect
        $this->artisan(CheckIfUpcomingStreamsAreLiveCommand::class)
            ->expectsOutput('There are no streams to update.')
            ->assertExitCode(0);
    }
}
