<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function showLinkRequestForm(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Send a reset link to the given user.
     */
    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email']);

        // Send reset link using default Password broker
        $status = Password::broker()->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }

    /**
     * Display the password reset view for the given token.
     */
    public function showResetForm(Request $request, ?string $token = null): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Reset the given user's password.
     */
    public function reset(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Attempt password reset
        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, string $password): void {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Password Anda berhasil diperbarui. Silakan login kembali.');
        }

        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }
}
