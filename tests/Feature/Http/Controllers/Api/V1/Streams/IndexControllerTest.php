<?php

namespace Tests\Feature\Http\Controllers\Api\V1\Streams;

use App\Models\Stream;
use Tests\TestCase;

class IndexControllerTest extends TestCase
{
    /** @test */
    public function it_shows_all_streams(): void
    {
        $stream = Stream::factory()->create([
            'title' => 'Stream #1',
            'youtube_id' => '1234',
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
            expect: $stream->id,
        )->assertJsonPath(
            path: '0.attributes.identifiers.youtube',
            expect: $stream->youtube_id,
        )->assertJsonPath(
            path: '0.type',
            expect: 'stream',
        )->assertJsonPath(
            path: '0.attributes.title',
            expect: $stream->title,
        );
    }

    /** @test */
    public function it_only_shows_approved_streams(): void
    {
        $stream = Stream::factory()->approved()->create();
        $stream2 = Stream::factory()->notApproved()->create();

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
