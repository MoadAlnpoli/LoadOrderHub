<?php

namespace App\Http\Controllers;

use App\Models\ModPack;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    /**
     * Rate a mod pack (upvote/downvote).
     */
    public function rate(Request $request, ModPack $modPack)
    {
        $request->validate([
            'is_upvote' => 'required|boolean',
        ]);

        $isUpvote = (bool) $request->is_upvote;

        // Fallback to first user for demo purposes if not logged in
        $userId = auth()->id() ?: User::first()?->id;

        if (!$userId) {
            return response()->json(['error' => 'User not found. Seed database.'], 400);
        }

        // Find or create rating
        $rating = Rating::where('user_id', $userId)
            ->where('mod_pack_id', $modPack->id)
            ->first();

        if ($rating) {
            if ($rating->is_upvote === $isUpvote) {
                // If they click the same button again, withdraw the vote
                $rating->delete();
            } else {
                // Change the vote
                $rating->update(['is_upvote' => $isUpvote]);
            }
        } else {
            // New vote
            Rating::create([
                'user_id' => $userId,
                'mod_pack_id' => $modPack->id,
                'is_upvote' => $isUpvote,
            ]);
        }

        // Recalculate upvotes/downvotes count
        $upvotes = Rating::where('mod_pack_id', $modPack->id)->where('is_upvote', true)->count();
        $downvotes = Rating::where('mod_pack_id', $modPack->id)->where('is_upvote', false)->count();

        // Update denormalized columns on ModPack
        $modPack->update([
            'upvotes' => $upvotes,
            'downvotes' => $downvotes,
        ]);

        return response()->json([
            'success' => true,
            'upvotes' => $upvotes,
            'downvotes' => $downvotes,
        ]);
    }
}
