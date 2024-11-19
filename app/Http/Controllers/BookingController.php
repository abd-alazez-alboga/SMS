<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{

    public function index()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        // For admin users, return all bookings
        if ($user->isAdmin()) {
            $bookings = Booking::all();
        } else {
            // For regular users, return only their bookings
            $bookings = Booking::where('user_id', Auth::id())->get();
        }

        return response()->json($bookings, 200);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'date' => 'required|date',
            'vehicle' => 'required|in:car,van,both',
            'number_of_passengers' => 'required|integer|min:1',
            'number_of_bags' => 'required|integer|min:1',
            'names' => 'required|array',
            'names.*' => 'required|string',
            'passport_photos' => 'required|array',
            'passport_photos.*' => 'string',
            'id_photos' => 'required|array',
            'id_photos.*' => 'string',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        // Ensure the number of names matches the number of passengers
        if (count($validated['names']) !== (int)$validated['number_of_passengers']) {
            return response()->json(['message' => 'The number of names must match the number of passengers.'], 422);
        }

        $validated['user_id'] = Auth::id();

        $booking = Booking::create($validated);

        return response()->json($booking, 201);
    }

    public function show($id)
    {
        $booking = Booking::find($id);
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        // Authorization check
        if (Auth::id() !== $booking->user_id && !$user->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($booking, 200);
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::find($id);
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        // Authorization check
        if (Auth::id() !== $booking->user_id && !$user->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'trip_id' => 'sometimes|required|exists:trips,id',
            'date' => 'sometimes|required|date',
            'vehicle' => 'sometimes|required|in:car,van,both',
            'number_of_passengers' => 'sometimes|required|integer|min:1',
            'number_of_bags' => 'required|integer|min:1',
            'names' => 'sometimes|required|array',
            'names.*' => 'required|string',
            'passport_photos' => 'sometimes|required|array',
            'passport_photos.*' => 'string',
            'id_photos' => 'sometimes|required|array',
            'id_photos.*' => 'string',
            'status' => 'sometimes|required|in:pending,confirmed,cancelled,completed',
        ]);

        if (isset($validated['number_of_passengers']) && isset($validated['names'])) {
            if (count($validated['names']) !== (int)$validated['number_of_passengers']) {
                return response()->json(['message' => 'The number of names must match the number of passengers.'], 422);
            }
        }

        $booking->update($validated);

        return response()->json($booking, 200);
    }

    public function destroy($id)
    {
        $booking = Booking::find($id);
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        // Authorization check
        if (Auth::id() !== $booking->user_id && !$user->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $booking->delete();

        return response()->json(['message' => 'Booking deleted successfully'], 200);
    }
}
