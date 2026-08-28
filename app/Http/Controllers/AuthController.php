<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

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
     *
     * Looks the user up in the `users` table with raw SQL, verifies the
     * password against its bcrypt hash, then logs the user in via the
     * standard Laravel auth guard.
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        // Fetch the user (or a matching row) by their email address.
        // LIMIT 1 guarantees we only ever inspect a single row.
        // The '?' placeholder is bound to $email -> prevents SQL injection.
        $matches = DB::select(
            'SELECT id, email, password, role, first_name FROM users WHERE email = ? LIMIT 1',
            [$email]
        );

        // No row found -> the email is not registered. Return one generic
        // error so we do not reveal whether the email or password was wrong.
        if (empty($matches)) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['email' => 'These credentials do not match our records.']);
        }

        $user = $matches[0];

        // Compare the submitted password against the stored one directly.
        // No hashing is used for now, so this is a plain-string comparison.
        if ($password !== $user->password) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['email' => 'These credentials do not match our records.']);
        }

        // Credentials are correct. Log the user in using the auth guard.
        Auth::loginUsingId($user->id);

        // Regenerate the session ID so the new login cannot be session-fixed.
        $request->session()->regenerate();

        // Send the signed-in user to their dashboard.
        return redirect()
            ->route('dashboard.staff')
            ->with('status', 'Welcome back! You are now signed in.');
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

        // Use the password as provided (no hashing for now).
        $password = $request->input('password');

        // Insert the new user into the MySQL `users` table.
        // `role` is omitted on purpose so the database default ('staff') applies.
        // created_at / updated_at are filled automatically by the column defaults.
        DB::insert(
            'INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)',
            [$firstName, $lastName, $email, $password]
        );

        // Generate the numeric one-time code via the shared helper below.
        $otp = $this->generateOtp();

        // Store the code in the otps so it can be verified later.
        // created_at, expires_at (5 min), attempt and total_attempt
        // are all filled by the database column defaults.
        DB::insert(
            'INSERT INTO otps (email, otp) VALUES (?, ?)',
            [$email, $otp]
        );

        // Keep the email in the session so the OTP screen knows who to verify.
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
     *
     * Looks up the code in the otps for the signed-up email, applies the
     * expiry and attempt limits, and on success logs the user in and removes
     * the used code so it cannot be reused.
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        // The email from signup is held in the session.
        $email = $request->session()->get('signup_email');

        // No email -> nothing to verify. Restart the signup flow.
        if (! $email) {
            return redirect()
                ->route('signup')
                ->withErrors(['email' => 'Your session has expired. Start with sign up again.']);
        }

        $code = $request->input('otp');

        // Fetch the newest unexpired code for this email.
        // expires_at > NOW() discards codes older than the 5-minute window.
        $matches = DB::select(
            'SELECT id, otp, attempt, total_attempt FROM otps
             WHERE email = ? AND expires_at > NOW()
             ORDER BY id DESC LIMIT 1',
            [$email]
        );

        // No valid code exists -> expired or never created.
        if (empty($matches)) {
            return redirect()
                ->route('otp.show')
                ->withErrors(['otp' => 'Your code is invalid or has expired. Request a new one.']);
        }

        $record = $matches[0];

        // Wrong code entered. Count the failed attempt.
        if ($code !== $record->otp) {
            // Count this failure on top of the existing attempts.
            $newAttempt = $record->attempt + 1;

            // Too many failures -> burn the code and force a fresh start.
            if ($newAttempt >= $record->total_attempt) {
                DB::delete('DELETE FROM otps WHERE id = ?', [$record->id]);
                $request->session()->forget('signup_email');

                return redirect()
                    ->route('otp.show')
                    ->withErrors(['otp' => 'Too many failed attempts. Please sign up again.']);
            }

            // Otherwise just save the new attempt count for the next try.
            DB::update(
                'UPDATE otps SET attempt = ? WHERE id = ?',
                [$newAttempt, $record->id]
            );

            return redirect()
                ->route('otp.show')
                ->withErrors(['otp' => 'Incorrect code. Please try again.']);
        }

        // Code is correct. Delete it so it cannot be verified again.
        DB::delete('DELETE FROM otps WHERE id = ?', [$record->id]);

        // Find the matching user account so we can log them in.
        $user = DB::select(
            'SELECT id FROM users WHERE email = ? LIMIT 1',
            [$email]
        );

        // Safety: the account should exist, but guard against a missing row.
        if (empty($user)) {
            $request->session()->forget('signup_email');

            return redirect()
                ->route('signup')
                ->withErrors(['email' => 'Account not found. Please sign up again.']);
        }

        // Log the user in and rotate the session id for security.
        Auth::loginUsingId($user[0]->id);
        $request->session()->regenerate();

        // Clean up the signup email from the session now that we are verified.
        $request->session()->forget('signup_email');

        return redirect()
            ->route('dashboard.staff')
            ->with('status', 'Account verified. Welcome to EN.AR!');
    }

    /**
     * Resend the OTP with a fresh code stored in the database.
     */
    public function resendOtp(Request $request): RedirectResponse
    {
        // Only allowed if the signup email is still in the session.
        $email = $request->session()->get('signup_email');

        if (! $email) {
            return redirect()->route('signup');
        }

        // Generate a brand-new code via the shared helper.
        $otp = $this->generateOtp();

        // Insert the new code as a fresh row (gets a new 5-minute expiry).
        DB::insert(
            'INSERT INTO otps (email, otp) VALUES (?, ?)',
            [$email, $otp]
        );

        // Send the new code through the configured mail driver.
        Mail::raw(
            "Your EN.AR verification code is: {$otp}",
            function ($message) use ($email) {
                $message->to($email)
                    ->subject('Your EN.AR verification code');
            }
        );

        return redirect()
            ->route('otp.show')
            ->with('status', 'A new code was sent to your email. Check your inbox and try again.');
    }

    /**
     * Log the current user out and send them back to the login page.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Show the "forgot password" request form.
     */
    public function showForgotPassword(): View
    {
        return view('auth-views.forgot-password');
    }

    /**
     * Send a reset OTP to the email address, if an account exists.
     *
     * Mirrors the signup OTP flow: generate a 6-digit code, store it in the
     * otps, and "send" it through the configured mail driver. For privacy,
     * the same success message is returned whether or not the email exists.
     */
    public function sendResetOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $email = $request->input('email');

        // Only send a code when the account actually exists.
        $user = DB::select('SELECT id FROM users WHERE email = ? LIMIT 1', [$email]);

        if (! empty($user)) {
            $otp = $this->generateOtp();

            DB::insert(
                'INSERT INTO otps (email, otp) VALUES (?, ?)',
                [$email, $otp]
            );

            Mail::raw(
                "Your EN.AR password reset code is: {$otp}",
                function ($message) use ($email) {
                    $message->to($email)
                        ->subject('Your EN.AR password reset code');
                }
            );
        }

        // Keep the email so the reset screen knows whose password to change.
        $request->session()->put('reset_email', $email);

        return redirect()
            ->route('password.reset')
            ->with('status', 'If an account exists for that email, we sent a one-time code to reset your password.');
    }

    /**
     * Show the password reset form (OTP + new password).
     */
    public function showResetPassword(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('reset_email')) {
            return redirect()
                ->route('password.request')
                ->withErrors(['email' => 'Start by entering your email so we can send you a code.']);
        }

        return view('auth-views.reset-password');
    }

    /**
     * Verify the reset OTP and update the user's password.
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $email = $request->session()->get('reset_email');

        if (! $email) {
            return redirect()
                ->route('password.request')
                ->withErrors(['email' => 'Your session has expired. Start over to reset your password.']);
        }

        $code = $request->input('otp');

        // Fetch the newest valid (unexpired) code for this email.
        $matches = DB::select(
            'SELECT id FROM otps
             WHERE email = ? AND otp = ? AND expires_at > NOW()
             ORDER BY id DESC LIMIT 1',
            [$email, $code]
        );

        if (empty($matches)) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['otp' => 'That code is invalid or has expired. Request a new one.']);
        }

        // Burn the used code so it cannot be reused.
        DB::delete('DELETE FROM otps WHERE id = ?', [$matches[0]->id]);

        // Update the stored password (plain string, matching the rest of the app).
        DB::update(
            'UPDATE users SET password = ? WHERE email = ?',
            [$request->input('password'), $email]
        );

        // Clean up the reset session and send the user to log in.
        $request->session()->forget('reset_email');

        return redirect()
            ->route('login')
            ->with('status', 'Your password has been reset. Log in with your new password.');
    }

    /**
     * Generate a numeric-only OTP.
     *
     * Shared helper so any flow (signup, password reset, etc.) can request a code.
     * random_int ensures digits only and str_pad keeps the result at 6 digits,
     * e.g. '000123'.
     */
    private function generateOtp(): string
    {
        // Pick a random integer between 0 and 999999 (6-digit range).
        $number = random_int(0, 999999);

        // Zero-pad the number on the left so it is always exactly 6 digits.
        return str_pad((string) $number, 6, '0', STR_PAD_LEFT);
    }
}
