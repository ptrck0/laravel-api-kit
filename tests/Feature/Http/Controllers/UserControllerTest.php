<?php

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;

use function Pest\Laravel\assertModelMissing;

const USER_RESPONSE = [
    'id',
    'name',
    'username',
    'email',
    'permissions',
    'roles',
];

it('can index users', function () {
    User::factory(10)->create();

    asAuthenticatedUser()->getJson(route('admin.users.index'))
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => USER_RESPONSE,
            ],
        ]);
});

it('can show a user', function () {
    User::factory(10)->create();

    asAuthenticatedUser()
        ->getJson(
            route(
                'admin.users.show',
                ['user' => User::query()->inRandomOrder()->first()]
            )
        )
        ->assertOk()
        ->assertJsonStructure([
            'data' => USER_RESPONSE,
        ]);
});

it('can create a user', function () {
    asAuthenticatedUser()
        ->postJson(
            route('admin.users.store'),
            [
                ...User::factory()->make(['password' => fake()->password()])->makeVisible('password')->toArray(),
                ...[
                    'roles' => Role::factory(2)->create()->pluck('id')->toArray(),
                    'permissions' => Permission::factory(2)->create()->pluck('id')->toArray(),
                ],
            ]
        )
        ->assertCreated()
        ->assertJsonStructure([
            'data' => USER_RESPONSE,
        ]);
    expect(User::has('roles')->count())->toBe(1)
        ->and(User::has('permissions')->count())->toBe(1)
        ->and(User::has('permissions')->first()?->permissions()->count())->toBe(2)
        ->and(User::has('roles')->first()?->roles()->count())->toBe(2);
});

it('can update a user', function () {
    $user = User::factory()->create();

    $name = $user->name;

    expect($user->roles()->count())->toBeZero()
        ->and($user->permissions()->count())->toBeZero();

    asAuthenticatedUser()
        ->patchJson(
            route('admin.users.update', ['user' => $user]),
            [
                ...User::factory()->make()->only(['name']),
                ...[
                    'roles' => Role::factory(2)->create()->pluck('id')->toArray(),
                    'permissions' => Permission::factory(2)->create()->pluck('id')->toArray(),
                ],
            ]
        )
        ->assertOk()
        ->assertJsonStructure([
            'data' => USER_RESPONSE,
        ]);

    $user->refresh();

    expect($name)->not()->toBe($user->name)
        ->and($user->roles()->count())->toBe(2)
        ->and($user->permissions()->count())->toBe(2);
});

it('can delete a user', function () {
    $user = User::factory()->create();

    asAuthenticatedUser()
        ->deleteJson(
            route('admin.users.destroy', ['user' => $user])
        )
        ->assertOk()
        ->assertJsonStructure([
            'success',
        ]);

    assertModelMissing($user);
});
