<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @OA\Info(
 *     title="My Laravel API",
 *     version="1.0.0",
 *     description="This is the API documentation for my Laravel app"
 * )
 * 
 * @OA\Tag(
 *     name="Posts",
 *     description="API Endpoints for Posts"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="sanctumAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="API Token",
 *     description="Use Laravel Sanctum authentication with bearer token or session cookie"
 * )
 */
class PostApiController extends Controller
{
    private $allowed_filters = ['status', 'scheduled_at'];

    /**
     * @OA\Get(
     *     path="/api/posts",
     *     security={{"sanctumAuth": {}}},
     *     tags={"Posts"},
     *     summary="Get paginated list of posts with platforms",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="List of posts",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/Post")
     *             ),
     *             @OA\Property(property="last_page", type="integer"),
     *             @OA\Property(property="per_page", type="integer"),
     *             @OA\Property(property="total", type="integer")
     *         )
     *     )
     * )
     */

    public function index()
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = auth()->user();
        // Check if the user has permission to view all posts
        if (auth()->user()->can('viewAny', Post::class)) {
            return QueryBuilder::for(Post::class)
                ->allowedFilters($this->allowed_filters)
                ->paginate()
                ->withQueryString()
                ->toJson();
        }
        // If the user can only view their own posts, filter by user ID
        return QueryBuilder::for($user->posts())
            ->allowedFilters($this->allowed_filters)
            ->paginate()->withQueryString()->toJson();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * @OA\Get(
     *     path="/api/posts/{id}",
     *     tags={"Posts"},
     *     summary="Get a post by ID with platforms",
     *     security={{"sanctumAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Post ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post details",
     *         @OA\JsonContent(ref="#/components/schemas/Post")
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Post not found")
     * )
     */
    public function show(Post $post)
    {
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        if (!auth()->user()->can('view', $post)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return $post->load('platforms');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
