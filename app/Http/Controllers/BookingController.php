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
            'user_id' => 'required|exists:users,id',
            'trip_id' => 'required|exists:trips,id',
            'number_of_passengers' => 'required|integer|min:1',
            'number_of_bags_of_wieght_10' => 'required|integer',
            'number_of_bags_of_wieght_23' => 'required|integer',
            'number_of_bags_of_wieght_30' => 'required|integer',
            'date' => 'required|date',
            'vehicle' => 'required|in:car,van,both',
            'name' => 'required|string',
            'entry_requirement' => 'in:Visa,Foreign Passport,Residency,eVisa',
            'passport_photo' => 'required|string',
            'ticket_photo' => 'required|string'
        ]);


        $validated['user_id'] = Auth::id();

        $booking = Booking::create([
            'user_id' => $validated['user_id'],
            'trip_id' => $validated['trip_id'],
            'number_of_passengers' => $validated['number_of_passengers'],
            'number_of_bags_of_wieght_10' => $validated['number_of_bags_of_wieght_10'],
            'number_of_bags_of_wieght_23' => $validated['number_of_bags_of_wieght_23'],
            'number_of_bags_of_wieght_30' => $validated['number_of_bags_of_wieght_30'],
            'date' => $validated['date'],
            'vehicle' => $validated['vehicle'],
            'name' => $validated['name'],
            'entry_requirement' => $validated['entry_requirement'],
            'passport_photo' => $validated['passport_photo'],
            'ticket_photo' => $validated['ticket_photo'],
            'status' => 'pending',
        ]);

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
            'user_id' => 'required|exists:users,id',
            'trip_id' => 'required|exists:trips,id',
            'number_of_passengers' => 'required|integer|min:1',
            'number_of_bags_of_wieght_10' => 'required|integer',
            'number_of_bags_of_wieght_23' => 'required|integer',
            'number_of_bags_of_wieght_30' => 'required|integer',
            'date' => 'required|date',
            'vehicle' => 'required|in:car,van,both',
            'name' => 'required|string',
            'entry_requirement' => 'in:Visa,Foreign Passport,Residency,eVisa',
            'passport_photo' => 'required|string',
            'ticket_photo' => 'required|string',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);


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
