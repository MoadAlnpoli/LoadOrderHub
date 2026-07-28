<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\ModPack;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, ModPack $modPack)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
            'rating_stars' => 'nullable|integer|min:1|max:5',
        ]);

        // In a live system, we require auth.
        // For local demo/testability, we fallback to the first user in the database.
        $userId = auth()->id() ?: User::first()?->id;

        if (!$userId) {
            return back()->with('error', 'Please seed the database first.');
        }

        $comment = Comment::create([
            'mod_pack_id' => $modPack->id,
            'user_id' => $userId,
            'parent_id' => $request->parent_id,
            'content' => $request->content,
            'rating_stars' => $request->rating_stars,
        ]);

        if ($request->ajax()) {
            $comment->load('user');
            return response()->json([
                'success' => true,
                'comment' => [
                    'id' => $comment->id,
                    'user_name' => $comment->user->name,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at->diffForHumans(),
                    'parent_id' => $comment->parent_id,
                    'rating_stars' => $comment->rating_stars,
                ]
            ]);
        }

        return back()->with('success', 'Comment posted successfully.');
    }
}
