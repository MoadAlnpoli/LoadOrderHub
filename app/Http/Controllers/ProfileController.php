<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ModPack;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Show the user profile page.
     */
    public function show(User $user = null)
    {
        // If no user is passed, display the logged-in user. Redirect if guest.
        if (!$user) {
            if (!Auth::check()) {
                return redirect()->route('login')->with('error', 'Please log in to view your profile.');
            }
            $user = Auth::user();
        }

        // Eager load relationships
        $user->load(['profile', 'comments.modPack', 'ratings' => function($q) {
            $q->where('is_upvote', true)->with('modPack.gameVersions.game');
        }]);

        // Ensure profile exists
        if (!$user->profile) {
            $user->profile()->create([
                'phone' => '-',
                'address' => '-',
                'bio' => 'Gaming enthusiast.',
            ]);
            $user->load('profile');
        }

        // Retrieve saved modpacks from JSON storage
        $savedPackIds = $user->profile->saved_packs ?? [];
        $savedPacks = ModPack::whereIn('id', $savedPackIds)
            ->with('gameVersions.game')
            ->withCount('mods')
            ->get();

        return view('profile.show', compact('user', 'savedPacks'));
    }

    /**
     * Update the user profile (including avatar upload).
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $profile = $user->profile;
        if (!$profile) {
            $profile = new Profile();
            $profile->user_id = $user->id;
        }

        $profile->phone = $request->phone ?? '-';
        $profile->address = $request->address ?? '-';
        $profile->bio = $request->bio;

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $profile->avatar = asset('storage/' . $path);
        }

        $profile->save();

        // Update password if filled
        if ($request->filled('password')) {
            $user->update([
                'password' => bcrypt($request->password),
            ]);
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Bookmark (Save/Unsave) a mod pack dynamically via AJAX.
     */
    public function toggleSave(ModPack $modPack)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $profile = $user->profile;
        if (!$profile) {
            $profile = $user->profile()->create([
                'phone' => '-',
                'address' => '-',
                'bio' => 'Gaming enthusiast.',
            ]);
        }

        $savedPacks = $profile->saved_packs ?? [];
        $packId = $modPack->id;

        if (in_array($packId, $savedPacks)) {
            // Remove from saved list
            $savedPacks = array_values(array_diff($savedPacks, [$packId]));
            $saved = false;
        } else {
            // Add to saved list
            $savedPacks[] = $packId;
            $saved = true;
        }

        $profile->update([
            'saved_packs' => $savedPacks,
        ]);

        return response()->json([
            'success' => true,
            'saved' => $saved,
        ]);
    }
}
