<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset password — EN.AR Workforce</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=IBM+Plex+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-page">

    {{-- ========== NAVBAR ========== --}}
    <x-navbar />

    {{-- ========== RESET PASSWORD ========== --}}
    <main class="auth-shell">
        <div class="auth-card">
            <div class="auth-card-head">
                <p class="auth-eyebrow">EN.AR Workforce</p>
                <h1 class="auth-title">Choose a new password</h1>
                <p class="auth-lead">
                    Enter the 6-digit code sent to
                    <strong class="otp-email">{{ session('reset_email', 'your email') }}</strong>
                    and your new password.
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

            <form method="POST" action="{{ route('password.update') }}" class="auth-form otp-form" novalidate>
                @csrf
                <input type="hidden" name="otp" id="otp" value="{{ old('otp') }}">

                <div class="field">
                    <label class="otp-label" for="otp-1">Reset code</label>
                    <div class="otp-boxes" role="group" aria-label="6-digit reset code">
                        <input id="otp-1" class="otp-box" type="text" inputmode="numeric" maxlength="1" autocomplete="one-time-code" aria-label="Digit 1">
                        <input class="otp-box" type="text" inputmode="numeric" maxlength="1" aria-label="Digit 2">
                        <input class="otp-box" type="text" inputmode="numeric" maxlength="1" aria-label="Digit 3">
                        <input class="otp-box" type="text" inputmode="numeric" maxlength="1" aria-label="Digit 4">
                        <input class="otp-box" type="text" inputmode="numeric" maxlength="1" aria-label="Digit 5">
                        <input class="otp-box" type="text" inputmode="numeric" maxlength="1" aria-label="Digit 6">
                    </div>
                </div>

                <div class="field">
                    <label for="password">New password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        autocomplete="new-password"
                        placeholder="At least 8 characters"
                        required
                    >
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirm new password</label>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        autocomplete="new-password"
                        placeholder="Re-enter your new password"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary auth-submit">Reset password</button>
            </form>

            <div class="otp-actions">
                <form method="POST" action="{{ route('password.email') }}" class="otp-resend-form">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('reset_email') }}">
                    <button type="submit" class="field-link otp-resend-btn">Resend code</button>
                </form>
            </div>

            <p class="auth-foot">
                <a href="{{ route('login') }}">Back to log in</a>
            </p>
        </div>
    </main>

    {{-- ========== FOOTER ========== --}}
    <x-footbar />

</body>
</html>
