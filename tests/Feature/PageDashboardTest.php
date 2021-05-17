<?php

namespace Tests\Feature;

use App\Http\Livewire\ImportYoutubeChannel;
use App\Http\Livewire\ImportYoutubeLiveStream;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageDashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_includes_livewire_youtube_import_stream_component(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertSeeLivewire(ImportYoutubeLiveStream::class);
    }

    /** @test */
    public function it_includes_livewire_youtube_import_channel_component(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertSeeLivewire(ImportYoutubeChannel::class);
    }
}
