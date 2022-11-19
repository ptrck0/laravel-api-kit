<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permission\StorePermissionRequest;
use App\Http\Requests\Permission\UpdatePermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Knuckles\Scribe\Attributes\ResponseFromFile;

/**
 * @group Permissions
 * @authenticated
 */
class PermissionController extends Controller
{
    public const DEFAULT_RELATIONS = ['roles'];

    /**
     * Display a listing of the permissions.
     *
     * @return AnonymousResourceCollection
     */
    #[ResponseFromFile('api-docs/example-responses/permission/index.json', 200)]
    public function index(): AnonymousResourceCollection
    {
        return PermissionResource::collection(
            Permission::with(self::DEFAULT_RELATIONS)->get()
        );
    }

    /**
     * Store a newly created permission in storage.
     *
     * @param  StorePermissionRequest  $request
     * @return PermissionResource
     */
    #[ResponseFromFile('api-docs/example-responses/permission/store.json', 201)]
    public function store(StorePermissionRequest $request): PermissionResource
    {
        $permission = Permission::create($request->validated());

        $permission->roles()->sync($request->get('roles', []));

        return new PermissionResource(
            $permission->load(self::DEFAULT_RELATIONS)
        );
    }

    /**
     * Display the specified permission.
     *
     * @param  Permission  $permission
     * @return PermissionResource
     */
    #[ResponseFromFile('api-docs/example-responses/permission/show.json', 200)]
    public function show(Permission $permission): PermissionResource
    {
        return new PermissionResource($permission->load(self::DEFAULT_RELATIONS));
    }

    /**
     * Update the specified permission in storage.
     *
     * @param  UpdatePermissionRequest  $request
     * @param  Permission  $permission
     * @return PermissionResource
     */
    #[ResponseFromFile('api-docs/example-responses/permission/update.json', 200)]
    public function update(UpdatePermissionRequest $request, Permission $permission): PermissionResource
    {
        $permission->update($request->validated());

        if ($request->has('roles')) {
            $permission->roles()->sync($request->get('roles', []));
        }

        return new PermissionResource($permission->load(self::DEFAULT_RELATIONS));
    }

    /**
     * Remove the specified permission from storage.
     *
     * @param  Permission  $permission
     * @return JsonResponse
     */
    #[ResponseFromFile('api-docs/example-responses/permission/destroy.json', 200)]
    public function destroy(Permission $permission): JsonResponse
    {
        $permission->delete();

        return response()->json([
            'success' => 'permission deleted',
        ]);
    }
}
