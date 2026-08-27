<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
     *
     * Writes the new user into the MySQL `users` table using raw SQL
     * (the project avoids Eloquent for data access), then generates a
     * 6-digit one-time code, stores it in the session, and "sends" it
     * via the configured mail driver (logs in local dev).
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

        // Grab the validated inputs into local variables for the queries below.
        $firstName = $request->input('first_name');
        $lastName = $request->input('last_name');
        $email = $request->input('email');
        $password = $request->input('password');

        // Check whether this email is already registered.
        // The '?' placeholder is bound to $email -> prevents SQL injection.
        $existing = DB::select('SELECT id FROM users WHERE email = ?', [$email]);

        // If a row came back, the email is taken. Stop here with an error.
        if (! empty($existing)) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['email' => 'This email is already registered. Try logging in instead.']);
        }

        // Hash the password with bcrypt so no plain-text passwords are stored.
        $hashedPassword = Hash::make($password);

        // Insert the new user into the MySQL `users` table.
        // `role` is omitted on purpose so the database default ('staff') applies.
        // created_at / updated_at are filled automatically by the column defaults.
        DB::insert(
            'INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)',
            [$firstName, $lastName, $email, $hashedPassword]
        );

        // Generate a 6-digit one-time code for email verification.
        // random_int(0, 999999) + str_pad guarantees a zero-padded 6-digit code.
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Keep the code and the email in the session so the OTP screen can verify them.
        $request->session()->put('otp_code', $otp);
        $request->session()->put('signup_email', $email);

        // "Send" the code through the configured mail driver.
        // With MAIL_MAILER=log the email is written to storage/logs/laravel.log.
        Mail::raw(
            "Your EN.AR verification code is: {$otp}",
            function ($message) use ($email) {
                $message->to($email)
                    ->subject('Your EN.AR verification code');
            }
        );

        return redirect()
            ->route('otp.show')
            ->with('status', 'We sent a one-time code to your email. Enter it to finish creating your account.');
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
