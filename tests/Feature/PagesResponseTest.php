<?php

it('can show the home page', function() {
    $this->get(route('home'))
        ->assertOk();
});

it('can show the feed', function() {
    $this->get('/feed')
        ->assertOk();
});

it('can show the archive', function() {
    $this->get(route('archive'))
        ->assertOk();
});

it('can show the calendar page', function() {
    $this->withoutExceptionHandling();
    $this->get(route('calendar.ics'))
        ->assertOk();
});

it('can privacy policy page', function() {
    $this->get(route('privacy.policy'))
        ->assertOk();
});
