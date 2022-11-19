<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Knuckles\Scribe\Attributes\ResponseFromFile;

/**
 * @group Roles
 * @authenticated
 */
class RoleController extends Controller
{
    private const DEFAULT_RELATIONS = ['permissions'];

    /**
     * Display a listing of the roles.
     *
     * @return AnonymousResourceCollection
     */
    #[ResponseFromFile('api-docs/example-responses/role/index.json', 200)]
    public function index(): AnonymousResourceCollection
    {
        return RoleResource::collection(
            Role::with(self::DEFAULT_RELATIONS)->get()
        );
    }

    /**
     * Store a newly created role in storage.
     *
     * @param  StoreRoleRequest  $request
     * @return RoleResource
     */
    #[ResponseFromFile('api-docs/example-responses/role/store.json', 201)]
    public function store(StoreRoleRequest $request): RoleResource
    {
        $role = Role::create($request->validated());

        $role->permissions()->sync($request->get('permissions', []));

        return new RoleResource(
            $role->load(self::DEFAULT_RELATIONS)
        );
    }

    /**
     * Display the specified role.
     *
     * @param  Role  $role
     * @return RoleResource
     */
    #[ResponseFromFile('api-docs/example-responses/role/show.json', 200)]
    public function show(Role $role): RoleResource
    {
        return new RoleResource($role->load(self::DEFAULT_RELATIONS));
    }

    /**
     * Update the specified role in storage.
     *
     * @param  UpdateRoleRequest  $request
     * @param  Role  $role
     * @return RoleResource
     */
    #[ResponseFromFile('api-docs/example-responses/role/update.json', 200)]
    public function update(UpdateRoleRequest $request, Role $role): RoleResource
    {
        $role->update($request->validated());

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->get('permissions', []));
        }

        return new RoleResource($role->load(self::DEFAULT_RELATIONS));
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  Role  $role
     * @return JsonResponse
     */
    #[ResponseFromFile('api-docs/example-responses/role/destroy.json', 200)]
    public function destroy(Role $role): JsonResponse
    {
        $role->delete();

        return response()->json([
            'success' => 'role deleted',
        ]);
    }
}
