<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use OpenApi\Attributes as OA;

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
    #[OA\Post(
        path: "/api/posts",
        tags: ["Posts"],
        summary: "Create a new post",
        description: "Create a new post with the authenticated user",
        operationId: "createPost",
        security: [
            ["sanctumAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            description: "Post data",
            required: true,
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(property: "title", type: "string", description: "Title of the post", example: "My First Post"),
                    new OA\Property(property: "content", type: "string", description: "Content of the post", example: "This is the content of my first post"),
                    new OA\Property(property: "image_url", type: "string", format: "url", nullable: true, description: "Image URL for the post", example: "https://example.com/image.jpg"),
                    new OA\Property(property: "scheduled_at", type: "string", format: "date-time", nullable: true, description: "Scheduled date and time for the post", example: "2025-05-25T12:00:00Z"),
                    new OA\Property(
                        property: "status",
                        ref: "#/components/schemas/PostStatusEnum"
                    ),
                    new OA\Property(property: "platform_id", type: "integer", format: "int64", description: "ID of the platform the post will be published on", example: 1)
                ],
                required: ["title", "content"]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Post created successfully",
                content: new OA\JsonContent(ref: "#/components/schemas/Post")
            ),
            new OA\Response(response: 401, description: "Unauthorized"),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    public function store(StorePostRequest $request)
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $post = auth()->user()->posts()->create($request->validated());
        $post->platforms()->attach($request->input('platform_id'));
        $post->load('platforms');
        return response()->json($post, 201);
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
    public function update(UpdatePostRequest $request, Post $post)
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Check if the user has permission to update the post
        if (!auth()->user()->can('update', $post)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Update the post with validated data
        $post->update($request->validated());

        // Update platforms if provided
        if ($request->has('platforms')) {
            $post->platforms()->sync($request->input('platforms'));
        }

        return response()->json($post->load('platforms'), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
        // Check if the user is authenticated
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Check if the user has permission to update the post
        if (!auth()->user()->can('delete', $post)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        // Delete the post
        $post->platforms()->detach();
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully'], 200);
    }
}
