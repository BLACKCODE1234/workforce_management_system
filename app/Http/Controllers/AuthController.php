<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin(): View
    {
        return view('auth-views.login');
    }

    /**
     * Handle a login attempt.
     * Real credential checks (raw SQL) will be wired when the users table is connected.
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        // Placeholder: route staff into their dashboard after a successful form submit.
        // Replace with DB::select + Auth::loginWhenReady once the schema is connected.
        return redirect()
            ->route('dashboard.staff')
            ->with('status', 'Signed in (demo). Real authentication will use the workforce database.');
    }

    /**
     * Show the signup form.
     */
    public function showSignup(): View
    {
        return view('auth-views.signup');
    }

    /**
     * Handle signup and move to OTP verification.
     * Real insert + mail OTP will be wired when the schema is connected.
     */
    public function signup(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['accepted'],
        ]);

        // Keep email available while the user is on the OTP screen.
        $request->session()->put('signup_email', $request->input('email'));

        return redirect()
            ->route('otp.show')
            ->with('status', 'We sent a one-time code to your email (demo). Enter any 6 digits to continue.');
    }

    /**
     * Show the OTP verification form.
     */
    public function showOtp(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('signup_email')) {
            return redirect()
                ->route('signup')
                ->withErrors(['email' => 'Start with sign up so we know where to send your code.']);
        }

        return view('auth-views.otp-verify');
    }

    /**
     * Verify the OTP and continue.
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $request->session()->forget('signup_email');

        return redirect()
            ->route('dashboard.staff')
            ->with('status', 'Account verified (demo). Real OTP checks will use email + database.');
    }

    /**
     * Resend the OTP (demo placeholder).
     */
    public function resendOtp(Request $request): RedirectResponse
    {
        if (! $request->session()->has('signup_email')) {
            return redirect()->route('signup');
        }

        return redirect()
            ->route('otp.show')
            ->with('status', 'A new code was sent (demo). Check your inbox and try again.');
    }
}
