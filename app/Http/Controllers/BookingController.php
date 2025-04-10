<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use App\Models\Attendee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function index()
    {
        return response()->json(Booking::with(['event', 'attendee'])->get());
    }

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
