<?php

use App\Actions\ImportVideoAction;
use App\Models\Channel;
use Illuminate\Support\Facades\Http;
use Tests\Fakes\YouTubeResponses;
use Tests\TestCase;

uses(TestCase::class);
uses(YouTubeResponses::class);

it('adds a channel id if channel already given', function () {
    Http::fake([
        '*videos*' => Http::response($this->singleVideoResponse()),
    ]);

    // Arrange
    $youTubeId = 'gzqJZQyfkaI';
    $channel = Channel::factory()->create(['platform_id' => 'UCNlUCA4VORBx8X-h-rXvXEg']);

    // Act
    $importedStream = (new ImportVideoAction())->handle($youTubeId);

    // Assert
    expect($importedStream->channel_id)->toEqual($channel->id);
});
