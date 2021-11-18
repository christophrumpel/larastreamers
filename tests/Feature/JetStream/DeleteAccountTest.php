<?php

use App\Models\User;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Http\Livewire\DeleteUserForm;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class);

test('user accounts can be deleted', function () {
    if (! Features::hasAccountDeletionFeatures()) {
        return $this->markTestSkipped('Account deletion is not enabled.');
    }

    $this->actingAs($user = User::factory()->create());

    $component = Livewire::test(DeleteUserForm::class)
                    ->set('password', 'password')
                    ->call('deleteUser');

    expect($user->fresh())->toBeNull();
});

test('correct password must be provided before account can be deleted', function () {
    if (! Features::hasAccountDeletionFeatures()) {
        return $this->markTestSkipped('Account deletion is not enabled.');
    }

    $this->actingAs($user = User::factory()->create());

    Livewire::test(DeleteUserForm::class)
                    ->set('password', 'wrong-password')
                    ->call('deleteUser')
                    ->assertHasErrors(['password']);

    $this->assertNotNull($user->fresh());
});
