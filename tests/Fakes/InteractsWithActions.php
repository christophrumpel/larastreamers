<?php

namespace Tests\Fakes;

use PHPUnit\Framework\Assert as PHPUnit;

trait InteractsWithActions
{
    protected array $expectedActions = [];

    protected array $handleActions = [];

    public function fakeAction(string $actionClassName): void
    {
        $this->expectedActions[$actionClassName] = $actionClassName;

        $this->mock($actionClassName)
            ->shouldReceive('handle')
            ->andReturnUsing(function() use ($actionClassName) {
                if (isset($this->handleActions[$actionClassName])) {
                    $this->handleActions[$actionClassName]++;
                } else {
                    $this->handleActions[$actionClassName] = 1;
                }

                unset($this->expectedActions[$actionClassName]);
            });
    }

    public function assertActionCalled(string $actionClassName): void
    {
        PHPUnit::assertArrayNotHasKey($actionClassName, $this->expectedActions);
    }
}
