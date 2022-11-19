<?php

use App\Models\User;

it('can log in', function () {
    $data = [
        'username' => fake()->email(),
        'password' => fake()->password(),
    ];

    User::factory()->create($data);

    $this->postJson(route('auth.login'), $data)
        ->assertOk()
        ->assertJsonStructure(['token']);
});

it('cannot log in with invalid credentials', function () {
    $user = User::factory()->create();

    $this->postJson(route('auth.login'), Arr::add($user->only('username'), 'password', 'test'))
        ->assertForbidden();
});

it('can log out', function () {
    $user = User::factory()->create();

    $user->createToken('test');

    expect($user->tokens()->count())->toBeOne();

    $this->actingAs($user)->postJson(route('auth.logout'))
        ->assertOk()
        ->assertJsonStructure(['success']);

    expect($user->tokens()->count())->toBe(0);
});
