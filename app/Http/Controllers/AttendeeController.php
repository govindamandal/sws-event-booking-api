<?php

namespace App\Http\Controllers;

use App\Models\Attendee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Attendees",
 *     description="API Endpoints for managing attendees"
 * )
 */
class AttendeeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/attendees",
     *     tags={"Attendees"},
     *     summary="List all attendees",
     *     @OA\Response(
     *         response=200,
     *         description="List of attendees",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Attendee"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Attendee::all());
    }

    /**
     * @OA\Post(
     *     path="/api/attendees",
     *     tags={"Attendees"},
     *     summary="Register a new attendee",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Attendee created",
     *         @OA\JsonContent(ref="#/components/schemas/Attendee")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|unique:attendees',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $attendee = Attendee::create($validator->validated());

        return response()->json($attendee, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/attendees/{id}",
     *     tags={"Attendees"},
     *     summary="Get attendee by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Attendee ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Attendee found",
     *         @OA\JsonContent(ref="#/components/schemas/Attendee")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Attendee not found"
     *     )
     * )
     */
    public function show($id)
    {
        $attendee = Attendee::find($id);

        if (! $attendee) {
            return response()->json(['message' => 'Attendee not found'], 404);
        }

        return response()->json($attendee);
    }

    /**
     * @OA\Put(
     *     path="/api/attendees/{id}",
     *     tags={"Attendees"},
     *     summary="Update attendee",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Attendee ID"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Jane Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="jane@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Attendee updated",
     *         @OA\JsonContent(ref="#/components/schemas/Attendee")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Attendee not found"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $attendee = Attendee::find($id);

        if (! $attendee) {
            return response()->json(['message' => 'Attendee not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'  => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|unique:attendees,email,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $attendee->update($validator->validated());

        return response()->json($attendee);
    }

    /**
     * @OA\Delete(
     *     path="/api/attendees/{id}",
     *     tags={"Attendees"},
     *     summary="Delete attendee",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Attendee ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Attendee deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Attendee not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $attendee = Attendee::find($id);

        if (! $attendee) {
            return response()->json(['message' => 'Attendee not found'], 404);
        }

        $attendee->delete();

        return response()->json(['message' => 'Attendee deleted successfully']);
    }
}
