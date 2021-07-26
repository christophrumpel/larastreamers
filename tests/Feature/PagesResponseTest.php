<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PagesResponseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_show_the_home_page(): void
    {
        $this->get(route('home'))
             ->assertOk();
    }

    /** @test */
    public function it_can_show_the_feed(): void
    {
        $this->get('/feed')
            ->assertOk();
    }

    /** @test */
    public function it_can_show_the_archive(): void
    {
        $this->get(route('archive'))
            ->assertOk();
    }

    /** @test */
    public function it_can_show_the_submission_page(): void
    {
        $this->get(route('submission'))
            ->assertOk();
    }

    /** @test */
    public function it_can_show_the_calendar_page(): void
    {
        $this->get(route('calendar.ics'))
            ->assertOk();
    }
}
