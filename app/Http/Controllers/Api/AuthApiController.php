<?php

namespace App\Http\Controllers\Api;

use OpenApi\Attributes as OA;
use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Http\Requests\LoginRequest;
use Symfony\Component\HttpFoundation\Response;

class AuthApiController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(Request $request): JsonResponse
    {
        $user = (new CreateNewUser())->create($request->input());
        Auth::login($user);
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' =>            new UserResource($user)
        ], Response::HTTP_CREATED);
    }

    #[OA\Post(
        path: '/api/login',
        summary: 'Login a user',
        description: 'Login a user and return a token',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', example: 'test@example.com'),
                    new OA\Property(property: 'password', type: 'string', example: 'password'),
                    new OA\Property(property: 'remember', type: 'boolean', example: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful login',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'token', type: 'string', example: 'your_api_token_here'),
                        new OA\Property(property: 'user', ref: '#/components/schemas/UserResource'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'email',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'The provided credentials are incorrect.')
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function login(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        $user = Auth::user();
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json(['token' => $token, 'user' => new UserResource($user)]);
    }
    #[OA\Post(
        path: '/api/logout',
        summary: 'Logout a user',
        description: 'Logout the authenticated user and invalidate the token',
        tags: ['Auth'],
        security: [['sanctumAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful logout',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Logged out successfully')
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.')
                    ]
                )
            ),
        ]
    )]
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully'], Response::HTTP_OK);
    }
}
