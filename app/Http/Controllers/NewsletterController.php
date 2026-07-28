<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate(['email' => 'required|email|max:255']);

        NewsletterSubscriber::firstOrCreate(
            ['email' => $request->email],
            ['is_active' => true]
        );

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => app()->getLocale() === 'ar'
                ? 'تم الاشتراك بنجاح! ستصلك أفضل المودات كل أسبوع.'
                : 'Subscribed! You\'ll get weekly top mods in your inbox.']);
        }

        return back()->with('success', 'Subscribed successfully!');
    }

    public function unsubscribe(string $token)
    {
        $sub = NewsletterSubscriber::where('token', $token)->firstOrFail();
        $sub->update(['is_active' => false]);

        return view('newsletter.unsubscribed');
    }
}
