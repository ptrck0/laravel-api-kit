<?php

use App\Models\Permission;

use App\Models\Role;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertModelMissing;

const PERMISSION_RESPONSE = [
    'id',
    'name',
    'guard_name',
    'roles',
];

it('can index permissions', function () {
    Permission::factory(10)->create();

    asAuthenticatedUser()->getJson(route('admin.permissions.index'))
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => PERMISSION_RESPONSE,
            ],
        ]);
});

it('can show a permission', function () {
    Permission::factory(10)->create();

    asAuthenticatedUser()
        ->getJson(
            route(
                'admin.permissions.show',
                ['permission' => Permission::query()->inRandomOrder()->first()]
            )
        )
        ->assertOk()
        ->assertJsonStructure([
            'data' => PERMISSION_RESPONSE,
        ]);
});

it('can create a permission', function () {
    asAuthenticatedUser()
        ->postJson(
            route('admin.permissions.store'),
            [
                ...Permission::factory()->make()->only(['name']),
                ...[
                    'roles' => Role::factory(2)->create()->pluck('id')->toArray(),
                ],
            ]
        )
        ->assertCreated()
        ->assertJsonStructure([
            'data' => PERMISSION_RESPONSE,
        ]);

    assertDatabaseCount((new Permission())->getTable(), 1);

    expect(Permission::first()->roles()->count())->toBe(2);
});

it('can update a permission', function () {
    $permission = Permission::factory()->create();

    expect($permission->roles()->count())->toBeZero();

    $name = $permission->name;

    asAuthenticatedUser()
        ->patchJson(
            route('admin.permissions.update', ['permission' => $permission]),
            [
                ...Permission::factory()->make()->only(['name']),
                ...[
                    'roles' => Role::factory(2)->create()->pluck('id')->toArray(),
                ],
            ]
        )
        ->assertOk()
        ->assertJsonStructure([
            'data' => PERMISSION_RESPONSE,
        ]);

    $permission->refresh();

    expect($name)->not()->toBe($permission->name)
        ->and($permission->roles()->count())->toBe(2);
});

it('can delete a permission', function () {
    $permission = Permission::factory()->create();

    asAuthenticatedUser()
        ->deleteJson(
            route('admin.permissions.destroy', ['permission' => $permission])
        )
        ->assertOk()
        ->assertJsonStructure([
            'success',
        ]);

    assertModelMissing($permission);
});

it('cannot assign non-existent roles', function () {
    asAuthenticatedUser()
        ->patchJson(
            route('admin.permissions.update', ['permission' => Permission::factory()->create()]),
            [
                'roles' => [fake()->randomNumber(4)],
            ]
        )
        ->assertUnprocessable();
});
