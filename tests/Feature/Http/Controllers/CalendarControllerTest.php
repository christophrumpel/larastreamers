<?php

use App\Models\Channel;
use App\Models\Stream;
use Illuminate\Support\Carbon;

it('shows all approved streams in calendar', function() {
    // Arrange
    Stream::factory()->approved()->withChannel()->create(['title' => 'Stream two years old', 'scheduled_start_time' => Carbon::now()->subYears(2), 'youtube_id' => '-2y']);
    Stream::factory()->approved()->withChannel()->create(['title' => 'Stream last year', 'scheduled_start_time' => Carbon::now()->subYear(), 'youtube_id' => '-1y']);
    Stream::factory()->approved()->withChannel()->create(['title' => 'Stream yesterday', 'scheduled_start_time' => Carbon::yesterday(), 'youtube_id' => '-1d']);
    Stream::factory()->approved()->withChannel()->create(['title' => 'Stream today', 'scheduled_start_time' => Carbon::now(), 'youtube_id' => 'now']);
    Stream::factory()->approved()->withChannel()->create(['title' => 'Stream tomorrow', 'scheduled_start_time' => Carbon::tomorrow(), 'youtube_id' => '1d']);
    Stream::factory()->approved()->withChannel()->create(['title' => 'Stream next week', 'scheduled_start_time' => Carbon::now()->addWeek(), 'youtube_id' => '1w']);
    Stream::factory()->approved()->withChannel()->create(['title' => 'Stream next month', 'scheduled_start_time' => Carbon::now()->addMonth(), 'youtube_id' => '1m']);
    Stream::factory()->approved()->withChannel()->create(['title' => 'Stream next year', 'scheduled_start_time' => Carbon::now()->addMonth(), 'youtube_id' => '1y']);

    // Act & Assert
    $this->get(route('calendar.ics'))
        ->assertHeader('Content-Type', 'text/calendar; charset=UTF-8')
        ->assertDontSee([
            'SUMMARY:Stream two years old',
            'DESCRIPTION:Stream two years old',
            'https://www.youtube.com/watch?v=-2y',
        ])
        ->assertSeeInOrder([
            'SUMMARY:Stream last year',
            'DESCRIPTION:Stream last year',
            'https://www.youtube.com/watch?v=-1y',
        ])
        ->assertSeeInOrder([
            'SUMMARY:Stream yesterday',
            'DESCRIPTION:Stream yesterday',
            'https://www.youtube.com/watch?v=-1d',
        ])
        ->assertSeeInOrder([
            'SUMMARY:Stream today',
            'DESCRIPTION:Stream today',
            'https://www.youtube.com/watch?v=now',
        ])
        ->assertSeeInOrder([
            'SUMMARY:Stream tomorrow',
            'DESCRIPTION:Stream tomorrow',
            'https://www.youtube.com/watch?v=1d',
        ])
        ->assertSeeInOrder([
            'SUMMARY:Stream next week',
            'DESCRIPTION:Stream next week',
            'https://www.youtube.com/watch?v=1w',
        ])
        ->assertSeeInOrder([
            'SUMMARY:Stream next month',
            'DESCRIPTION:Stream next month',
            'https://www.youtube.com/watch?v=1m',
        ])
        ->assertSeeInOrder([
            'SUMMARY:Stream next year',
            'DESCRIPTION:Stream next year',
            'https://www.youtube.com/watch?v=1y',
        ]);

    // TODOS: We cannot test the description at the end of the DESCRIPTION field
    // after a specific length, the generated output cuts of the the string
    // so we cannot assert this string anymore
    // that's why the description is not in the string.
    /** @see \Spatie\IcalendarGenerator\Builders\ComponentBuilder::chipLine() */
});

it('shows only approved streams in calendar', function() {
    // Arrange
    Stream::factory()->approved()->withChannel()->create(['title' => 'Stream approved', 'scheduled_start_time' => Carbon::yesterday()]);
    Stream::factory()->notApproved()->withChannel()->create(['title' => 'Stream not approved', 'scheduled_start_time' => Carbon::yesterday()->subDay()]);

    // Act & Assert
    $this->get(route('calendar.ics'))
        ->assertHeader('Content-Type', 'text/calendar; charset=UTF-8')
        ->assertDontSee([
            'SUMMARY:Stream not approved',
        ])
        ->assertSeeInOrder([
            'SUMMARY:Stream approved',
        ]);
});

it('can filter streams by single language code', function() {
    Stream::factory()->for(Channel::factory()->create(['name' => 'Laravel']))->create(['title' => 'English Test Stream', 'language_code' => 'en']);
    Stream::factory()->for(Channel::factory()->create(['name' => 'Laravel']))->create(['title' => 'Spanish Test Stream', 'language_code' => 'es']);

    $this->get(route('calendar.ics', ['languages' => 'en']))
        ->assertSee('English Test Stream')
        ->assertDontSee('Spanish Test Stream');
});

it('can filter streams by multiple language codes', function() {
    Stream::factory()->for(Channel::factory()->create(['name' => 'Laravel']))->create(['title' => 'English Test Stream', 'language_code' => 'en']);
    Stream::factory()->for(Channel::factory()->create(['name' => 'Laravel']))->create(['title' => 'Spanish Test Stream', 'language_code' => 'es']);
    Stream::factory()->for(Channel::factory()->create(['name' => 'Laravel']))->create(['title' => 'French Test Stream', 'language_code' => 'fr']);

    $this->get(route('calendar.ics', ['languages' => 'en,es']))
        ->assertSee('English Test Stream')
        ->assertSee('Spanish Test Stream')
        ->assertDontSee('French Test Stream');
});

it('can download one calendar item', function() {
    $stream = Stream::factory()
        ->for(Channel::factory()->create(['name' => 'My Channel']))
        ->create([
            'title' => 'Single Stream',
            'scheduled_start_time' => Carbon::now(),
            'youtube_id' => '1234',
        ]);

    $this
        ->get(route('calendar.ics.stream', $stream))
        ->assertHeader('Content-Type', 'text/calendar; charset=UTF-8')
        ->assertSeeInOrder([
            'SUMMARY:Single Stream',
            'DESCRIPTION:Single Stream\nMy Channel\nhttps://www.youtube.com/watch?v=1234',
        ]);
});

it('removes carriage returns', function() {
    $stream = Stream::factory()
        ->for(Channel::factory())
        ->create(['description' => "\rJoin me on a journ"]);

    $this
        ->get(route('calendar.ics.stream', $stream))
        ->assertDontSee("\rJoin me on a journ")
        ->assertSee('Join me on a journ');
});
