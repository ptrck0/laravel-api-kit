<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Knuckles\Scribe\Attributes\ResponseFromFile;

/**
 * @group Users
 * @authenticated
 */
class UserController extends Controller
{
    public const DEFAULT_RELATIONS = ['roles', 'roles.permissions','permissions'];

    /**
     * Display a listing of the users.
     *
     * @return AnonymousResourceCollection
     */
    #[ResponseFromFile('api-docs/example-responses/user/index.json', 200)]
    public function index(): AnonymousResourceCollection
    {
        return UserResource::collection(
            User::with(self::DEFAULT_RELATIONS)->get()
        );
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  StoreUserRequest  $request
     * @return UserResource
     */
    #[ResponseFromFile('api-docs/example-responses/user/store.json', 201)]
    public function store(StoreUserRequest $request): UserResource
    {
        $user = User::create($request->validated());

        if ($request->has('permissions')) {
            $user->permissions()->sync($request->get('permissions', []));
        }

        if ($request->has('roles')) {
            $user->roles()->sync($request->get('roles', []));
        }

        return new UserResource(
            $user->load(self::DEFAULT_RELATIONS)
        );
    }

    /**
     * Display the specified user.
     *
     * @param  User  $user
     * @return UserResource
     */
    #[ResponseFromFile('api-docs/example-responses/user/show.json', 200)]
    public function show(User $user): UserResource
    {
        return new UserResource(
            $user->load(self::DEFAULT_RELATIONS)
        );
    }

    /**
     * Update the specified user in storage.
     *
     * @param  UpdateUserRequest  $request
     * @param  User  $user
     * @return UserResource
     */
    #[ResponseFromFile('api-docs/example-responses/user/update.json', 200)]
    public function update(UpdateUserRequest $request, User $user): UserResource
    {
        $user->update($request->validated());

        if ($request->has('permissions')) {
            $user->permissions()->sync($request->get('permissions', []));
        }

        if ($request->has('roles')) {
            $user->roles()->sync($request->get('roles', []));
        }

        return new UserResource(
            $user->load(self::DEFAULT_RELATIONS)
        );
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  User  $user
     * @return JsonResponse
     */
    #[ResponseFromFile('api-docs/example-responses/user/destroy.json', 200)]
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json([
            'success' => 'user deleted',
        ]);
    }
}
