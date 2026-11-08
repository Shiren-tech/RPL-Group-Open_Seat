<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show Register Form
    public function showRegister() {
        return view('auth.register');
    }

    // Handle Register Process
    public function register(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'dob' => 'required|date',
            'hobby' => 'required|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'dob' => $validated['dob'],
            'hobby' => $validated['hobby'],
            'pfp' => 'default.png', // default profile picture
            'total_xp' => 0,
            'orb_level' => 1,
            'role' => 'customer',
        ]);

        Auth::login($user);
        return redirect()->route('profile');
    }

    // Show Login Form
    public function showLogin() {
        return view('auth.login');
    }

    // Handle Login Process
    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('profile');
        }

        return back()->withErrors(['email' => 'Invalid credentials provided.']);
    }

    // Logout
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
