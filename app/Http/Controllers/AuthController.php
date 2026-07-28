<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            if (Auth::user()->is_admin) {
                return redirect()->route('admin.dashboard')->with('success', 'Logged in as Admin.');
            }

            return redirect()->route('home')->with('success', 'Logged in successfully.');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Show the registration form.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle registration post.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'required|accepted',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'is_admin' => false, // Default is regular user
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Account created successfully.');
    }

    /**
     * Log out of application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Logged out successfully.');
    }

    /**
     * Show forgot password form.
     */
    public function showForgotPassword()
    {
        return view('auth.forgot_password');
    }

    /**
     * Simulate sending password reset link.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $token = bin2hex(random_bytes(20));
        $email = $request->email;

        // Render simulated delivery screen
        return view('auth.reset_simulated', compact('token', 'email'));
    }

    /**
     * Show reset password form.
     */
    public function showResetPassword($token, Request $request)
    {
        $email = $request->get('email', '');
        return view('auth.reset_password', compact('token', 'email'));
    }

    /**
     * Update user password.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->update([
                'password' => bcrypt($request->password),
            ]);
            
            Auth::login($user);
            return redirect()->route('home')->with('success', 'Password reset successfully. You are now logged in!');
        }

        return back()->withErrors(['email' => 'Could not reset password at this time.']);
    }
}
