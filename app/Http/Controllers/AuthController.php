<?php

namespace App\Http\Controllers;

use App\Models\BlacklistedEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Regsiter
    public function registerCreate()
    {
        return view('auth.signup');
    }

    // Store Register
    public function registerStore(Request $request)
    {
        // Validate input
        $request->validate([
            'username' => 'required|string|unique:users,name',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        // Check Blacklist
        if (BlacklistedEmail::where('email', $request->email)->exists()) {
            return back()->withErrors([
                'email' => 'Akun anda sudah tidak bisa digunakan.'
            ]);
        }

        // Create user
        $user = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Set role to job seeker
        $user->role = 'viewer';
        $user->save();

        // Log the user in
        Auth::login($user);

        // Redirect
        return redirect(route('profile.index'));
    }

    // Login
    public function loginCreate()
    {
        return view('auth.login');
    }

    // Store Login
    public function loginStore(Request $request)
    {
        // Validate Input
        $credentials = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Check Blacklist
        if (BlacklistedEmail::where('email', $request->email)->exists()) {
            return back()->withErrors([
                'email' => 'Akun anda sudah tidak bisa digunakan.'
            ]);
        }

        // Check Credentials
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect(route('profile.index'));
        }

        throw ValidationException::withMessages([
            'credentials' => 'Email atau password salah'
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->regenerate();

        return redirect()->route('login');
    }
}
