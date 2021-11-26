<?php

use App\Models\Stream;
use Tests\TestCase;


it('shows all streams', function () {
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
});

it('only shows approved streams', function () {
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
});
