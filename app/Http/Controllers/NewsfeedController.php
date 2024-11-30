<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Newsfeed;
use App\Models\User;
use App\Notifications\NewNewsfeedNotification;
use Illuminate\Http\Request;

class NewsfeedController extends Controller
{
    /**
     * Display a listing of the newsfeeds.
     */
    public function index()
    {
        $newsfeeds = Newsfeed::all();
        return response()->json($newsfeeds, 200);
    }

    /**
     * Store a newly created newsfeed in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'images' => 'required|array',
            'images.*' => 'string',
            'title_en' => 'required|string|max:255',
            'description_en' => 'required|string',
            'title_ar' => 'required|string|max:255',
            'description_ar' => 'required|string',
        ]);

        $newsfeed = Newsfeed::create($validated);
        // Notify all users
        $users = User::all();
        foreach ($users as $user) {
            $user->notify(new NewNewsfeedNotification($newsfeed));
        }
        return response()->json($newsfeed, 201);
    }

    /**
     * Display the specified newsfeed.
     */
    public function show($id)
    {
        $newsfeed = Newsfeed::find($id);

        if (!$newsfeed) {
            return response()->json(['message' => 'Newsfeed not found'], 404);
        }

        return response()->json($newsfeed, 200);
    }

    /**
     * Update the specified newsfeed in storage.
     */
    public function update(Request $request, $id)
    {
        $newsfeed = Newsfeed::find($id);

        if (!$newsfeed) {
            return response()->json(['message' => 'Newsfeed not found'], 404);
        }

        $validated = $request->validate([
            'images' => 'sometimes|required|array',
            'images.*' => 'string',
            'title_en' => 'sometimes|required|string|max:255',
            'description_en' => 'sometimes|required|string',
            'title_ar' => 'sometimes|required|string|max:255',
            'description_ar' => 'sometimes|required|string',
        ]);

        $newsfeed->update($validated);

        return response()->json($newsfeed, 200);
    }

    /**
     * Remove the specified newsfeed from storage.
     */
    public function destroy($id)
    {
        $newsfeed = Newsfeed::find($id);

        if (!$newsfeed) {
            return response()->json(['message' => 'Newsfeed not found'], 404);
        }

        $newsfeed->delete();

        return response()->json(['message' => 'Newsfeed deleted successfully'], 200);
    }
}
