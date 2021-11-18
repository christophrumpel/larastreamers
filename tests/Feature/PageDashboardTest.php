<?php

use App\Http\Livewire\ImportYouTubeChannel;
use App\Http\Livewire\ImportYouTubeLiveStream;
use App\Models\User;
use Tests\TestCase;

uses(TestCase::class);

it('includes livewire youtube import stream component', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('dashboard'))
        ->assertSeeLivewire(ImportYouTubeLiveStream::class);
});

it('includes livewire youtube import channel component', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('dashboard'))
        ->assertSeeLivewire(ImportYouTubeChannel::class);
});
