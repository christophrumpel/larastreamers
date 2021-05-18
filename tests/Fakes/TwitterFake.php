<?php


namespace Tests\Fakes;

use PHPUnit\Framework\Assert as PHPUnit;

class TwitterFake
{

    private int $tweetsSent = 0;

    public function tweet(): void
    {
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
}
