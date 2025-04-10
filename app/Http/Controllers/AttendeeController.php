<?php

namespace App\Http\Controllers;

use App\Models\Attendee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendeeController extends Controller
{
    public function index()
    {
        return response()->json(Attendee::all());
    }

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

    public function show($id)
    {
        $attendee = Attendee::find($id);

        if (! $attendee) {
            return response()->json(['message' => 'Attendee not found'], 404);
        }

        return response()->json($attendee);
    }

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
