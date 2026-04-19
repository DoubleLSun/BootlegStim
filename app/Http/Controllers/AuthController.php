<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ── Login ────────────────────────────────────────────────────

    public function showLogin()
    {
        // If already authenticated, redirect to profile
        if (Auth::check()) {
            return redirect()->route('profile.show', Auth::user());
        } // Otherwise, show login form
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('profile.show', Auth::user()));
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'These credentials do not match our records.']);
    }

    // ── Register ─────────────────────────────────────────────────

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('profile.show', Auth::user());
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // validate check if variable matches with User $fillabl
        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        // once validated, create the user with default values for steam_level, status, and last_online_at
        // done with mass assignment, protected by the $fillable property in the User model
        $user = User::create([
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'password'    => Hash::make($validated['password']),
            'steam_level' => 0,
            'status'      => 'online',
            'last_online_at' => now(),
        ]);

        // Log the user in immediately after registration
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('profile.show', $user);
    }

    // ── Logout ───────────────────────────────────────────────────

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}