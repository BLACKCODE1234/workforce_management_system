<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP — EN.AR Workforce</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=IBM+Plex+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-page">

    {{-- ========== NAVBAR ========== --}}
    <x-navbar />

    {{-- ========== OTP VERIFY ========== --}}
    <main class="auth-shell">
        <div class="auth-card">
            <div class="auth-card-head">
                <div class="otp-icon" aria-hidden="true">
                    <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="6" y="12" width="36" height="26" rx="6" stroke="currentColor" stroke-width="2.5"/>
                        <path d="M6 18l18 12 18-12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <p class="auth-eyebrow">Step 2 of 2</p>
                <h1 class="auth-title">Enter verification code</h1>
                <p class="auth-lead">
                    We sent a 6-digit code to
                    <strong class="otp-email">{{ session('signup_email', 'your email') }}</strong>.
                    Enter it below to finish creating your account.
                </p>
            </div>

            @if (session('status'))
                <div class="auth-alert auth-alert-ok" role="status">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="auth-alert" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('otp.verify') }}" class="auth-form otp-form" novalidate>
                @csrf
                <input type="hidden" name="otp" id="otp" value="{{ old('otp') }}">

                <div class="field">
                    <label class="otp-label" for="otp-1">Verification code</label>
                    <div class="otp-boxes" role="group" aria-label="6-digit verification code">
                        <input id="otp-1" class="otp-box" type="text" inputmode="numeric" maxlength="1" autocomplete="one-time-code" aria-label="Digit 1">
                        <input class="otp-box" type="text" inputmode="numeric" maxlength="1" aria-label="Digit 2">
                        <input class="otp-box" type="text" inputmode="numeric" maxlength="1" aria-label="Digit 3">
                        <input class="otp-box" type="text" inputmode="numeric" maxlength="1" aria-label="Digit 4">
                        <input class="otp-box" type="text" inputmode="numeric" maxlength="1" aria-label="Digit 5">
                        <input class="otp-box" type="text" inputmode="numeric" maxlength="1" aria-label="Digit 6">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary auth-submit">Verify and continue</button>
            </form>

            <div class="otp-actions">
                <p class="otp-hint">Code expires in a fewminutes.</p>
                <form method="POST" action="{{ route('otp.resend') }}" class="otp-resend-form">
                    @csrf
                    <button type="submit" class="field-link otp-resend-btn">Resend code</button>
                </form>
            </div>

            <p class="auth-foot">
                Wrong email?
                <a href="{{ route('signup') }}">Go back to sign up</a>
                ·
                <a href="{{ route('login') }}">Log in</a>
            </p>
        </div>
    </main>

    {{-- ========== FOOTER ========== --}}
    <x-footbar />

</body>
</html>
