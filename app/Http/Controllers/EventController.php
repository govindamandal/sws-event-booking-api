<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Resources\EventResource;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Events",
 *     description="API Endpoints for managing Events"
 * )
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Use a bearer token to access these endpoints",
 *     name="Authorization",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="sanctum"
 * )
 */
class EventController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/events",
     *     summary="List events with optional search and date filters",
     *     tags={"Events"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search events by title",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="Filter events by date (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Number of events per page",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paginated list of events",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Event"))
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = Event::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('title', 'like', '%' . $search . '%');
        }

        if ($request->has('date')) {
            $query->whereDate('date', $request->get('date'));
        }

        $perPage = $request->get('limit', 10);
        $events = $query->paginate($perPage);

        return EventResource::collection($events);
    }

    /**
     * @OA\Post(
     *     path="/api/events",
     *     summary="Create a new event",
     *     tags={"Events"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","date","location","capacity"},
     *             @OA\Property(property="title", type="string", example="Tech Conference 2025"),
     *             @OA\Property(property="description", type="string", example="A full-day event on AI & Cloud"),
     *             @OA\Property(property="date", type="string", format="date", example="2025-07-25"),
     *             @OA\Property(property="location", type="string", example="India"),
     *             @OA\Property(property="capacity", type="integer", example=300)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Event created", @OA\JsonContent(ref="#/components/schemas/Event")),
     *     @OA\Response(response=422, description="Validation failed")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'required|date',
            'location'    => 'required|string|max:100',
            'capacity'    => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();
        $validated['user_id'] = $request->user()->id;

        $event = Event::create($validated);

        return new EventResource($event);
    }

    /**
     * @OA\Get(
     *     path="/api/events/{id}",
     *     summary="Get a specific event",
     *     tags={"Events"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the event",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Event found", @OA\JsonContent(ref="#/components/schemas/Event")),
     *     @OA\Response(response=404, description="Event not found")
     * )
     */
    public function show(Event $event)
    {
        return new EventResource($event);
    }

    /**
     * @OA\Put(
     *     path="/api/events/{id}",
     *     summary="Update an existing event",
     *     tags={"Events"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the event to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Updated Conference"),
     *             @OA\Property(property="description", type="string", example="Updated description"),
     *             @OA\Property(property="date", type="string", format="date", example="2025-08-01"),
     *             @OA\Property(property="location", type="string", example="New Delhi"),
     *             @OA\Property(property="capacity", type="integer", example=500)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Event updated", @OA\JsonContent(ref="#/components/schemas/Event")),
     *     @OA\Response(response=422, description="Validation failed")
     * )
     */
    public function update(Request $request, Event $event)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'sometimes|required|date',
            'location'    => 'sometimes|required|string|max:100',
            'capacity'    => 'sometimes|required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $event->update($validator->validated());

        return new EventResource($event);
    }

    /**
     * @OA\Delete(
     *     path="/api/events/{id}",
     *     summary="Delete an event",
     *     tags={"Events"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the event",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Event deleted"),
     *     @OA\Response(response=404, description="Event not found")
     * )
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return response()->json(['message' => 'Event deleted successfully']);
    }
}
