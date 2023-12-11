<?php

use App\Models\Channel;
use App\Models\Stream;
use App\Services\YouTube\StreamData;
use Illuminate\Support\Carbon;
use Spatie\TestTime\TestTime;

it('shows given streams on home page', function () {
    // Arrange
    Stream::factory()->for(Channel::factory()->create(['name' => 'My Channel']))->create(['title' => 'Stream #1', 'scheduled_start_time' => Carbon::now()->addDays(), 'youtube_id' => '1234', 'language_code' => 'en']);
    Stream::factory()->for(Channel::factory()->create(['name' => 'My Channel']))->create(['title' => 'Stream #2', 'scheduled_start_time' => Carbon::now()->addDays(2), 'youtube_id' => '12345', 'language_code' => 'fr']);
    Stream::factory()->for(Channel::factory()->create(['name' => 'My Channel']))->create(['title' => 'Stream #3', 'scheduled_start_time' => Carbon::now()->addDays(3), 'youtube_id' => '123456', 'language_code' => 'es']);

    // Act & Assert
    $this->get(route('home'))
        ->assertSee('Stream #1')
        ->assertSee('https://www.youtube.com/watch?v=1234')
        ->assertSee('My Channel')
        ->assertSee('Stream #2')
        ->assertSee('French')
        ->assertSee('https://www.youtube.com/watch?v=12345')
        ->assertSee('Stream #3')
        ->assertSee('Spanish')
        ->assertSee('https://www.youtube.com/watch?v=123456');
});

it('shows from closest to farthest', function () {
    // Arrange
    Stream::factory()
        ->withChannel()
        ->withTitle('Stream #1')
        ->withScheduledStartTime(Carbon::tomorrow())
        ->create();
    Stream::factory()
        ->withChannel()
        ->withTitle('Stream #2')
        ->withScheduledStartTime(Carbon::tomorrow()->addDay())
        ->create();
    Stream::factory()
        ->withChannel()
        ->withTitle('Stream #3')
        ->withScheduledStartTime(Carbon::tomorrow()->addDays(2))
        ->create();

    ray()->queries();

    // Act & Assert
    $this->get(route('home'))
        ->assertSeeInOrder(['Stream #1', 'Stream #2', 'Stream #3']);
});

it('shows unique names for today and tomorrow instead of whole date', function () {
    $this->withoutExceptionHandling();
    // Arrange
    Stream::factory()->for(Channel::factory()->create(['name' => 'My Channel']))->create(['title' => 'Stream #1', 'scheduled_start_time' => Carbon::today()->hour(2)]);
    Stream::factory()->for(Channel::factory()->create(['name' => 'My Channel']))->create(['title' => 'Stream #2', 'scheduled_start_time' => Carbon::tomorrow()]);

    // Act & Assert
    $this->get(route('home'))
        ->assertDontSee(today()->format('D d.m.Y'))
        ->assertSee('Today')
        ->assertDontSee(Carbon::tomorrow()->format('D d.m.Y'))
        ->assertSee('Tomorrow');
});

it('does not show old streams', function () {
    // Arrange
    Stream::factory()->for(Channel::factory()->create(['name' => 'My Channel']))->finished()->create(['title' => 'Stream finished']);
    Stream::factory()->for(Channel::factory()->create(['name' => 'My Channel']))->live()->create(['title' => 'Stream live']);
    Stream::factory()->for(Channel::factory()->create(['name' => 'My Channel']))->upcoming()->create(['title' => 'Stream upcoming']);

    // Act & Assert
    $this
        ->get(route('home'))
        ->assertSee('Stream live')
        ->assertSee('Stream upcoming')
        ->assertDontSee('Stream finished');
});

it('does not show deleted streams', function () {
    // Arrange
    Stream::factory()->for(Channel::factory()->create(['name' => 'My Channel']))->deleted()->create(['title' => 'Stream deleted']);
    Stream::factory()->for(Channel::factory()->create(['name' => 'My Channel']))->upcoming()->create(['title' => 'Stream upcoming']);

    // Act & Assert
    $this
        ->get(route('home'))
        ->assertSee('Stream upcoming')
        ->assertDontSee('Stream deleted');
});

it('marks live streams', function () {
    // Arrange
    $stream = Stream::factory()->for(Channel::factory()->create(['name' => 'My Channel']))->upcoming()->create(['title' => 'Stream #1']);

    // Act & Assert
    $this->get(route('home'))
        ->assertSee('Stream #1')
        ->assertDontSee('>live</span>', false);

    $stream->update(['status' => StreamData::STATUS_LIVE]);

    $this->get(route('home'))
        ->assertSee('Stream #1')
        ->assertSee('>live</span>', false);
});

it('shows footer links', function () {
    // Arrange
    $twitterLink = 'https://twitter.com/larastreamers';
    $githubLink = 'https://github.com/christophrumpel/larastreamers';

    // Act & Assert
    $this->get(route('home'))
        ->assertSee($twitterLink)
        ->assertSee($githubLink);
});

it('adds not button webcal link if no streams', function () {
    $this->get(route('home'))
        ->assertDontSee('webcal://');
});

it('adds button webcal link if no streams', function () {
    Stream::factory()
        ->for(Channel::factory()->create(['name' => 'My Channel']))
        ->upcoming()
        ->create();

    $this->get(route('home'))
        ->assertSee('webcal://');
});

it('will show the time till upcoming stream in preview component', function () {
    // Arrange
    TestTime::freeze();
    Stream::factory()
        ->upcoming()
        ->withChannel()
        ->create([
            'scheduled_start_time' => now()->addHours(2)->addSecond(),
        ]);

    // Act & Assert
    $this->get(route('home'))
        ->assertSeeText('Starts 2 hours from now');
});

it('will show the time passed since the stream started in preview component', function () {
    TestTime::freeze();
    Stream::factory()
        ->live()
        ->withChannel()
        ->create([
            'actual_start_time' => now()->subMinutes(7),
        ]);

    // Act & Assert
    $this->get(route('home'))
        ->assertSeeText('Started 7 minutes ago');
});

it('will show the time passed since the stream even if actual start missing', function () {
    TestTime::freeze();
    Stream::factory()
        ->live()
        ->withChannel()
        ->create([
            'scheduled_start_time' => now()->subMinutes(7),
        ]);

    // Act & Assert
    $this->get(route('home'))
        ->assertSeeText('Started 7 minutes ago');
});
