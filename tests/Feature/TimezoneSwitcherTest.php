<?php

namespace Tests\Feature;

use App\Http\Livewire\TimezoneSwitcher;
use Livewire\Livewire;
use Tests\TestCase;

class TimezoneSwitcherTest extends TestCase
{
    /** @test **/
    public function it_redirects_when_different_timezone_was_selected(): void
    {
    	// Arrange & Act & Assert
        Livewire::test(TimezoneSwitcher::class, ['timezones' => ['Europe/London', 'Europe/Vienna']])
            ->set('currentTimezone', 'Europe/London')
            ->assertRedirect('/?timezone=Europe%2FLondon');
    }
}
