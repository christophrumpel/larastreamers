<?php

namespace Tests\Fakes;

use PHPUnit\Framework\Assert as PHPUnit;

class SubmitStreamActionFake
{
    private bool $wasHandleMethodCalled = false;

    public function handle(): void
    {
        $this->wasHandleMethodCalled = true;
    }

    public function handleMethodWasCalled(): void
    {
        PHPUnit::assertTrue($this->wasHandleMethodCalled);
    }
}
