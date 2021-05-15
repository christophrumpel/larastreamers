<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PagesResponseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_successful_response_for_home_page(): void
    {
        $this->withoutExceptionHandling();

        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_show_the_feed(): void
    {
        $response = $this->get('/feed');

        $response->assertStatus(200);
    }
}
