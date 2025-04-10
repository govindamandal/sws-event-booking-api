<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/bookings",
     *     summary="Get all bookings with attendee and event",
     *     tags={"Bookings"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Booking"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Booking::with(['event', 'attendee'])->get());
    }

    /**
     * @OA\Post(
     *     path="/api/bookings",
     *     summary="Create a booking",
     *     tags={"Bookings"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"event_id","attendee_id"},
     *             @OA\Property(property="event_id", type="integer", example=1),
     *             @OA\Property(property="attendee_id", type="integer", example=3)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Booking created",
     *         @OA\JsonContent(ref="#/components/schemas/Booking")
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Booking conflict (duplicate or full event)"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id'    => 'required|exists:events,id',
            'attendee_id' => 'required|exists:attendees,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $event = Event::find($request->event_id);
        $attendee_id = $request->attendee_id;

        // Check for duplicate booking
        $existing = Booking::where('event_id', $event->id)
                           ->where('attendee_id', $attendee_id)
                           ->exists();

        if ($existing) {
            return response()->json(['message' => 'Attendee has already booked this event.'], 409);
        }

        // Check capacity (if capacity is used)
        $currentBookings = Booking::where('event_id', $event->id)->count();
        if ($event->capacity && $currentBookings >= $event->capacity) {
            return response()->json(['message' => 'Event is fully booked.'], 409);
        }

        $booking = Booking::create([
            'event_id'    => $event->id,
            'attendee_id' => $attendee_id,
        ]);

        return response()->json($booking->load(['event', 'attendee']), 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/bookings/{id}",
     *     summary="Cancel a booking",
     *     tags={"Bookings"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Booking ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Booking cancelled successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Booking not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $booking = Booking::find($id);

        if (! $booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        $booking->delete();

        return response()->json(['message' => 'Booking cancelled successfully']);
    }
}
