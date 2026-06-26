<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $email = $request->input('email');
        $user = \App\Models\User::where('email', $email)->first();
        $ip = $request->ip();
        $userAgent = $request->userAgent();

        if ($user) {
            if (\Illuminate\Support\Facades\Hash::check($request->input('password'), $user->password)) {
                if (! $user->is_active) {
                    \App\Models\LoginLog::create([
                        'user_id' => $user->id,
                        'email' => $email,
                        'ip_address' => $ip,
                        'user_agent' => $userAgent,
                        'status' => 'deactivated',
                    ]);

                    throw ValidationException::withMessages([
                        'email' => 'Akun Anda dinonaktifkan. Silakan hubungi administrator.',
                    ]);
                }

                // Log login success
                Auth::login($user, $request->boolean('remember'));

                \App\Models\LoginLog::create([
                    'user_id' => $user->id,
                    'email' => $email,
                    'ip_address' => $ip,
                    'user_agent' => $userAgent,
                    'status' => 'success',
                ]);
            } else {
                // Wrong password
                \App\Models\LoginLog::create([
                    'user_id' => $user->id,
                    'email' => $email,
                    'ip_address' => $ip,
                    'user_agent' => $userAgent,
                    'status' => 'failed',
                ]);

                throw ValidationException::withMessages([
                    'email' => 'Email atau password tidak sesuai.',
                ]);
            }
        } else {
            // User not found
            \App\Models\LoginLog::create([
                'user_id' => null,
                'email' => $email,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'status' => 'failed',
            ]);

            throw ValidationException::withMessages([
                'email' => 'Email atau password tidak sesuai.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'))
            ->with('success', 'Selamat datang kembali.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}
