<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up — EN.AR Workforce</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=IBM+Plex+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-page">

    {{-- ========== NAVBAR ========== --}}
    <x-navbar />

    {{-- ========== SIGNUP ========== --}}
    <main class="auth-shell">
        <div class="auth-card">
            <div class="auth-card-head">
                <p class="auth-eyebrow">EN.AR Workforce</p>
                <h1 class="auth-title">Create your account</h1>
                <p class="auth-lead">Sign up with your work email. You’ll verify with a one-time code next.</p>
            </div>

            @if ($errors->any())
                <div class="auth-alert" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('signup.submit') }}" class="auth-form" novalidate>
                @csrf

                <div class="field-grid">
                    <div class="field">
                        <label for="first_name">First name</label>
                        <input
                            id="first_name"
                            type="text"
                            name="first_name"
                            value="{{ old('first_name') }}"
                            autocomplete="given-name"
                            placeholder="Ama"
                            required
                        >
                    </div>

                    <div class="field">
                        <label for="last_name">Last name</label>
                        <input
                            id="last_name"
                            type="text"
                            name="last_name"
                            value="{{ old('last_name') }}"
                            autocomplete="family-name"
                            placeholder="Mensah"
                            required
                        >
                    </div>
                </div>

                <div class="field">
                    <label for="email">Work email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        placeholder="you@enar.local"
                        required
                    >
                </div>

                <div class="field">
                    <label for="password">Password</label>
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
                    <label for="password_confirmation">Confirm password</label>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        autocomplete="new-password"
                        placeholder="Re-enter your password"
                        required
                    >
                </div>

                <label class="check-row">
                    <input type="checkbox" name="terms" value="1" @checked(old('terms')) required>
                    <span>I agree to use this system for EN.AR Limited workforce purposes only</span>
                </label>

                <button type="submit" class="btn btn-primary auth-submit">Continue to verification</button>
            </form>

            <p class="auth-foot">
                Already have an account?
                <a href="{{ route('login') }}">Log in</a>
            </p>
        </div>
    </main>

    {{-- ========== FOOTER ========== --}}
    <x-footbar />

</body>
</html>
