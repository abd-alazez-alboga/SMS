<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\User;
use App\Notifications\NewTripNotification;
use Illuminate\Http\Request;

class TripController extends Controller
{

    public function index()
    {
        $trips = Trip::all();
        return response()->json($trips, 200);
    }

    public function show($id)
    {
        $trip = Trip::find($id);

        if (!$trip) {
            return response()->json(['message' => 'Trip not found'], 404);
        }

        return response()->json($trip, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pickup_location_ar' => 'required|string|max:255',
            'destination_ar' => 'required|string|max:255',
            'pickup_location_en' => 'required|string|max:255',
            'destination_en' => 'required|string|max:255',
            'images' => 'required|array',
            'images.*' => 'string',
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
            'price' => 'required|integer|min:0'
        ]);


        $trip = Trip::create($validated);
        
        return response()->json($trip, 201);
    }



    public function update(Request $request, $id)
    {
        $trip = Trip::find($id);

        if (!$trip) {
            return response()->json(['message' => 'Trip not found'], 404);
        }

        $validated = $request->validate([
            'pickup_location_ar' => 'required|string|max:255',
            'destination_ar' => 'required|string|max:255',
            'pickup_location_en' => 'required|string|max:255',
            'destination_en' => 'required|string|max:255',
            'images' => 'required|array',
            'images.*' => 'string',
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
            'price' => 'required|integer|min:0'
        ]);


        $trip->update($validated);

        return response()->json($trip, 200);
    }


    public function destroy($id)
    {
        $trip = Trip::find($id);

        if (!$trip) {
            return response()->json(['message' => 'Trip not found'], 404);
        }

        $trip->delete();

        return response()->json(['message' => 'Trip deleted successfully'], 200);
    }
}
