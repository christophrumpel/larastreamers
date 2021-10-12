<?php

namespace Tests\Feature;

use App\Http\Livewire\ImportYouTubeChannel;
use App\Http\Livewire\ImportYouTubeLiveStream;
use App\Models\User;
use Tests\TestCase;

class PageDashboardTest extends TestCase
{
    /** @test */
    public function it_includes_livewire_youtube_import_stream_component(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertSeeLivewire(ImportYouTubeLiveStream::class);
    }

    /** @test */
    public function it_includes_livewire_youtube_import_channel_component(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertSeeLivewire(ImportYouTubeChannel::class);
    }
}
