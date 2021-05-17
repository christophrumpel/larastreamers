<?php

namespace Tests\Feature;

use App\Models\Stream;
use App\Services\Youtube\StreamData;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\TestTime\TestTime;
use Tests\TestCase;

class TweetTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_only_tweets_streams_that_are_going_live_once()
    {
        TestTime::freeze();

        $stream = Stream::factory()->create([
            'status' => StreamData::STATUS_UPCOMING
        ]);

        $this->assertFalse($stream->refresh()->hasBeenTweeted());

        $stream->update(['status' => StreamData::STATUS_LIVE]);

        $this->assertTrue($stream->refresh()->hasBeenTweeted());

        $originallyTweetedAt = $stream->tweeted_at;
        TestTime::addMinute();
        $stream->update(['status' => StreamData::STATUS_LIVE]);
        $this->assertEquals($stream->refresh()->tweeted_at->timestamp, $originallyTweetedAt->timestamp);
    }


}
