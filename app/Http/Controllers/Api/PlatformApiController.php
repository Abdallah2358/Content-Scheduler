<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Platform;
use Auth;
use Illuminate\Http\Client\Request;
use OpenApi\Attributes as OA;

class PlatformApiController extends Controller
{

    #[OA\Get(
        path: "/api/platforms",
        tags: ["Platforms"],
        summary: "List available platforms",
        operationId: "listPlatforms",
        security: [
            ["sanctumAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "page",
                in: "query",
                description: "Page number for pagination",
                required: false,
                schema: new OA\Schema(type: "integer", default: 1)
            )

        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of platforms",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "current_page", type: "integer"),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(ref: "#/components/schemas/Platform")),
                        new OA\Property(property: "last_page", type: "integer"),
                        new OA\Property(property: "per_page", type: "integer")
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized")
        ]
    )]
    public function index()
    {
        return Platform::all();
    }

    #[OA\Get(
        path: "/api/platforms/{platform}/toggle",
        tags: ["Platforms"],
        summary: "Toggle platform disabled status",
        operationId: "togglePlatform",
        security: [
            ["sanctumAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "platform",
                in: "path",
                description: "ID of the platform to toggle",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Platform toggled successfully",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "message", type: "string")
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized"),
            new OA\Response(response: 403, description: "Forbidden")
        ]
    )]
    public function toggle(Request $request, Platform $platform)
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Check if the user has permission to disable the platform
        if (!auth()->user()->can('toggle', $platform)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        // Toggle the platform's disabled status
        if (Auth::user()->disabled_platforms()->where('platform_id', $platform->id)->exists()) {
            Auth::user()->disabled_platforms()->detach($platform->id);
            return response()->json(['message' => 'Platform enabled successfully'], 200);
        }

        Auth::user()->disabled_platforms()->attach($platform->id);
        return response()->json(['message' => 'Platform disabled successfully'], 200);
    }
}
