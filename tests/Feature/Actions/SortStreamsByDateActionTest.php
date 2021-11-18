<?php

use App\Actions\SortStreamsByDateAction;
use App\Models\Stream;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Carbon;
use Tests\TestCase;


it('groups streams by date', function () {
    // Arrange
    $streams = Stream::factory()->count(3)
        ->state(new Sequence(
            ['scheduled_start_time' => Carbon::today()],
            ['scheduled_start_time' => Carbon::tomorrow()],
            ['scheduled_start_time' => Carbon::tomorrow()->addDay()],
        ))->create();

    $prepareStreamsAction = new SortStreamsByDateAction;

    // Act
    $preparedStreams = $prepareStreamsAction->handle($streams);

    // Assert
    expect($preparedStreams->keys()[0])->toEqual('Today');
    expect($preparedStreams->keys()[1])->toEqual('Tomorrow');
    expect($preparedStreams->keys()[2])->toEqual(Carbon::tomorrow()->addDay()->format('D, M jS Y'));
});

it('orders streams from current to upcoming', function () {
    $this->travelTo(Carbon::parse('2021-06-11 00:00'));

    // Arrange
    $streams = Stream::factory()->count(3)
        ->state(new Sequence(
            ['scheduled_start_time' => Carbon::today()],
            ['scheduled_start_time' => Carbon::tomorrow()],
            ['scheduled_start_time' => Carbon::tomorrow()->addDay()],
        ))->create();

    $prepareStreamsAction = new SortStreamsByDateAction;

    // Act
    $preparedStreams = $prepareStreamsAction->handle($streams);

    $this->assertEquals([
        'Today',
        'Tomorrow',
        'Sun, Jun 13th 2021',
    ], $preparedStreams->keys()->toArray());
});
