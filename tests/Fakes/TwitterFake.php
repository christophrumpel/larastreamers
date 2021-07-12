<?php

namespace Tests\Fakes;

use PHPUnit\Framework\Assert as PHPUnit;

class TwitterFake
{
    private int $tweetsSent = 0;
    private string $lastTweetStatus;

    public function tweet(string $status): void
    {
        $this->lastTweetStatus = $status;
        $this->tweetsSent++;
    }

    public function assertTweetWasSent(): void
    {
        PHPUnit::assertGreaterThan(0, $this->tweetsSent);
    }

    public function assertNoTweetsWereSent(): void
    {
        PHPUnit::assertEquals(0, $this->tweetsSent);
    }

    public function assertLastTweetMessageWas(string $expectedStatus): void
    {
        PHPUnit::assertEquals($expectedStatus, $this->lastTweetStatus);
    }
}
