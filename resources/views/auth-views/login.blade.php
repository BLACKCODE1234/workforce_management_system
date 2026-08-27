<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in — EN.AR Workforce</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=IBM+Plex+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-page">

    {{-- ========== NAVBAR ========== --}}
    <x-navbar />

    {{-- ========== LOGIN ========== --}}
    <main class="auth-shell">
        <div class="auth-card">
            <div class="auth-card-head">
                <p class="auth-eyebrow">EN.AR Workforce</p>
                <h1 class="auth-title">Welcome back</h1>
                <p class="auth-lead">Sign in to open the workspace for your role.</p>
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

            <form method="POST" action="{{ route('login.submit') }}" class="auth-form" novalidate>
                @csrf

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
                    <div class="field-row">
                        <label for="password">Password</label>
                        <a href="#" class="field-link">Forgot password?</a>
                    </div>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        autocomplete="current-password"
                        placeholder="Enter your password"
                        required
                    >
                </div>

                <label class="check-row">
                    <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                    <span>Keep me signed in on this device</span>
                </label>

                <button type="submit" class="btn btn-primary auth-submit">Log in</button>
            </form>

            <p class="auth-foot">
                New to EN.AR?
                <a href="{{ route('signup') }}">Create an account</a>
            </p>
        </div>
    </main>

    {{-- ========== FOOTER ========== --}}
    <x-footbar />

</body>
</html>
