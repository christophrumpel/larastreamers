<?php

namespace Tests\Feature\API;

use App\Models\Stream;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class EndpointStreamsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_shows_all_streams(): void
    {
        $stream = Stream::factory()->create([
            'title' => 'Stream #1',
            'scheduled_start_time' => Carbon::now()->addDays(value: 2),
            'youtube_id' => '1234',
            'channel_title' => 'My Channel',
            'language_code' => 'en',
            'approved_at' => Carbon::now()->subDay(),
        ]);

        $response = $this->json(
            method: 'GET',
            uri: '/api/v1/streams',
        );

        $response->assertStatus(
            status: 200,
        )->assertHeader(
            headerName: 'Content-Type',
            value: 'application/vnd.api+json',
        )->assertJsonPath(
            path: '0.id',
            expect: $stream->youtube_id,
        )->assertJsonPath(
            path: '0.type',
            expect: 'stream',
        )->assertJsonPath(
            path: '0.attributes.title',
            expect: $stream->title,
        );
    }

    /**
     * @test
     */
    public function it_only_show_approved_streams(): void
    {
        $stream = Stream::factory()->create([
            'title' => 'Stream #1',
            'scheduled_start_time' => Carbon::now()->addDays(value: 2),
            'youtube_id' => '1234',
            'channel_title' => 'My Channel',
            'language_code' => 'en',
            'approved_at' => Carbon::now()->subDay(),
        ]);
        $stream2 = Stream::factory()->create([
            'title' => 'Stream #2',
            'scheduled_start_time' => Carbon::now()->addDays(value: 3),
            'youtube_id' => '4321',
            'channel_title' => 'My Channel',
            'language_code' => 'en',
            'approved_at' => null,
        ]);

        $response = $this->json(
            method: 'GET',
            uri: '/api/v1/streams',
        );

        $this->assertCount(
            expectedCount: 1,
            haystack: $response->json(),
        );
    }
}
