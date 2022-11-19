<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Auth;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\ResponseFromFile;
use Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Authentication
 * @authenticated
 */
class AuthController extends Controller
{
    /**
     * Login
     *
     * @param  LoginRequest  $request
     * @return JsonResponse
     */
    #[ResponseFromFile('api-docs/example-responses/auth/login.json', 200)]
    #[ResponseFromFile('api-docs/example-responses/auth/login_error.json', 403)]
    public function login(LoginRequest $request): JsonResponse
    {
        if (Auth::attempt([
            'username' => $request->username,
            'password' => $request->password, ])) {
            $token = $request->user()->createToken($request->username);

            return response()->json([
                'token' => $token->plainTextToken,
            ]);
        }

        return response()->json([
            'error' => 'invalid credentials',
        ], Response::HTTP_FORBIDDEN);
    }

    /**
     * Logout
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    #[ResponseFromFile('api-docs/example-responses/auth/logout.json', 200)]
    public function logout(Request $request): JsonResponse
    {
        Auth::user()?->tokens()->delete();

        return response()->json([
            'success' => 'logged out',
        ]);
    }
}
