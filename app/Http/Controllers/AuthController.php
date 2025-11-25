<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registerCreate()
    {
        return view('auth.signup');
    }

    public function registerStore(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Set role to job seeker
        $user->role = 'viewer';
        $user->save();

        // Log the user in
        Auth::login($user);

        // Redirect
        return redirect(route('profile.index'))->withInput();
    }

    public function loginCreate()
    {
        return view('auth.login');
    }

    public function loginStore(Request $request)
    {
        // Validate Input
        $credentials = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Check Credentials
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect(route('profile.index'));
        }

        return back()->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->regenerate();

        return redirect()->route('login');
    }
}
