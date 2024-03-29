<?php

use App\Livewire\ImportYouTubeChannel;
use App\Livewire\ImportYouTubeLiveStream;
use App\Models\User;

it('includes livewire youtube import stream component', function() {
    $this->actingAs(User::factory()->create())
        ->get(route('dashboard'))
        ->assertSeeLivewire(ImportYouTubeLiveStream::class);
});

it('includes livewire youtube import channel component', function() {
    $this->actingAs(User::factory()->create())
        ->get(route('dashboard'))
        ->assertSeeLivewire(ImportYouTubeChannel::class);
});
