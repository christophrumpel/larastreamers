<?php

namespace Tests\Feature\Resources\Views\Pages\Partials\Header;

use App\Models\Stream;
use Spatie\TestTime\TestTime;
use Tests\TestCase;

class PreviewTest extends TestCase
{
    /** @test */
    public function it_will_render_the_correct_relative_date_for_a_scheduled_stream(): void
    {
        TestTime::freeze();
        $stream = Stream::factory()
            ->upcoming()
            ->withChannel()
            ->create([
                'scheduled_start_time' => now()->addHours(2)->addSecond(),
            ]);

        $this->view('pages.partials.header.preview', [
            'upcomingStream' => $stream,
        ])->assertSee('2 hours from now');
    }

    /** @test */
    public function it_will_render_the_correct_relative_date_for_a_running_stream(): void
    {
        TestTime::freeze();
        $stream = Stream::factory()
            ->live()
            ->withChannel()
            ->create([
                'scheduled_start_time' => now()->subMinutes(7),
            ]);

        $this->view('pages.partials.header.preview', [
            'upcomingStream' => $stream,
        ])->assertSee('Started  7 minutes ago');
    }
}
