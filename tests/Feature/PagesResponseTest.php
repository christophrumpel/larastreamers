<?php

namespace Tests\Feature;

use Tests\TestCase;

class PagesResponseTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function it_shows_successful_response_for_home_page(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
