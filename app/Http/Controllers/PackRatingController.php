<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ModPack;
use App\Models\PackRating;

class PackRatingController extends Controller
{
    public function store(Request $request, ModPack $pack)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $rating = PackRating::updateOrCreate(
            ['mod_pack_id' => $pack->id, 'user_id' => auth()->id()],
            ['rating' => $request->rating]
        );

        return response()->json(['message' => __('Rating saved successfully.'), 'rating' => $rating->rating]);
    }
}
