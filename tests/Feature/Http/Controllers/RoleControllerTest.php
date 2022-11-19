<?php

use App\Models\Permission;
use App\Models\Role;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertModelMissing;

const ROLE_RESPONSE = [
    'id',
    'name',
    'guard_name',
    'permissions',
];

it('can index roles', function () {
    Role::factory(10)->create();

    asAuthenticatedUser()->getJson(route('admin.roles.index'))
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => ROLE_RESPONSE,
            ],
        ]);
});

it('can show a role', function () {
    Role::factory(10)->create();

    asAuthenticatedUser()
        ->getJson(
            route(
                'admin.roles.show',
                ['role' => Role::query()->inRandomOrder()->first()]
            )
        )
        ->assertOk()
        ->assertJsonStructure([
            'data' => ROLE_RESPONSE,
        ]);
});

it('can create a role', function () {
    asAuthenticatedUser()
        ->postJson(
            route('admin.roles.store'),
            [
                ...Role::factory()->make()->only(['name']),
                ...[
                    'permissions' => Permission::factory(2)->create()->pluck('id')->toArray(),
                ],
            ]
        )
        ->assertCreated()
        ->assertJsonStructure([
            'data' => ROLE_RESPONSE,
        ]);

    assertDatabaseCount((new Role())->getTable(), 1);

    expect(Role::first()->permissions()->count())->toBe(2);
});

it('can update a role', function () {
    $role = Role::factory()->create();

    $name = $role->name;

    expect($role->permissions()->count())->toBeZero();

    asAuthenticatedUser()
        ->patchJson(
            route('admin.roles.update', ['role' => $role]),
            [
                ...Role::factory()->make()->only(['name']),
                ...[
                    'permissions' => Permission::factory(2)->create()->pluck('id')->toArray(),
                ],
            ]
        )
        ->assertOk()
        ->assertJsonStructure([
            'data' => ROLE_RESPONSE,
        ]);

    $role->refresh();

    expect($name)->not()->toBe($role->name)
        ->and($role->permissions()->count())->toBe(2);
});

it('can delete a role', function () {
    $role = Role::factory()->create();

    asAuthenticatedUser()
        ->deleteJson(
            route('admin.roles.destroy', ['role' => $role])
        )
        ->assertOk()
        ->assertJsonStructure([
            'success',
        ]);

    assertModelMissing($role);
});

it('cannot assign non-existent permissions', function () {
    asAuthenticatedUser()
        ->patchJson(
            route('admin.roles.update', ['role' => Role::factory()->create()]),
            [
                'permissions' => [fake()->randomNumber(4)],
            ]
        )
        ->assertUnprocessable();
});
