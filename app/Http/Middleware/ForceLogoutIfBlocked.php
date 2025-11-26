<?php

namespace App\Http\Middleware;

use App\Models\BlacklistedEmail;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ForceLogoutIfBlocked
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $email = Auth::user()->email;

            // Check if the email is blacklisted
            if (BlacklistedEmail::where('email', $email)->exists()) {

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'email' => 'Akun anda telah diblokir.',
                ]);
            }
        }
        return $next($request);
    }
}
