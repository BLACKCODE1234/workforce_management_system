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
                <p class="auth-eyebrow">Step 2 of 2</p>
                <h1 class="auth-title">Check your email</h1>
                <p class="auth-lead">
                    Enter the one-time code sent to
                    <strong>{{ session('signup_email', 'your email') }}</strong>.
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

            <form method="POST" action="{{ route('otp.verify') }}" class="auth-form" novalidate>
                @csrf

                <div class="field">
                    <label for="otp">Verification code</label>
                    <input
                        id="otp"
                        class="otp-input"
                        type="text"
                        name="otp"
                        inputmode="numeric"
                        autocomplete="one-time-code"
                        maxlength="6"
                        placeholder="6-digit code"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary auth-submit">Verify and continue</button>
            </form>

            <p class="auth-foot">
                Didn’t get a code?
                <a href="{{ route('signup') }}">Try again</a>
                ·
                <a href="{{ route('login') }}">Back to log in</a>
            </p>
        </div>
    </main>

    {{-- ========== FOOTER ========== --}}
    <x-footbar />

</body>
</html>
