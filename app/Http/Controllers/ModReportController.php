<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mod;
use App\Models\ModReport;

class ModReportController extends Controller
{
    public function store(Request $request, Mod $mod)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        ModReport::create([
            'mod_id' => $mod->id,
            'user_id' => auth()->id(), // null if not logged in, adjust according to your logic
            'reason' => $request->reason,
            'status' => 'active'
        ]);

        return response()->json(['message' => __('Report submitted successfully.')]);
    }
}
