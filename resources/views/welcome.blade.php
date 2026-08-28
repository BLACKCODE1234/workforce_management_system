<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EN.AR Workforce — One record for every person on the roster</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=IBM+Plex+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    {{-- ========== NAVBAR ========== --}}
    <x-navbar />

    {{-- ========== HERO ========== --}}
    <section class="hero">
        <div class="wrap hero-layout">
            <div class="hero-copy">
                <span class="eyebrow">EN.AR Limited · Internal system</span>
                <h1>One record for every<br>person on the <em>roster</em>.</h1>
                <p class="lede">Units, positions, leave, and status — kept in one place instead of scattered across spreadsheets. Sign in to see your workspace, whatever your role.</p>
                <div class="hero-ctas">
                    <a href="{{ route('signup') }}" class="btn btn-primary">Create an account</a>
                    <a href="#how" class="btn btn-ghost">See how it works</a>
                </div>
                <p class="hero-note">Access is scoped to your role automatically — no separate setup needed.</p>
            </div>

            <div class="hero-media">
                <div class="hero-media-frame">
                    <img
                        src="{{ asset('images/tech.jpg') }}"
                        alt="EN.AR Limited workforce"
                        class="hero-media-img"
                    >
                </div>
            </div>
        </div>
    </section>

    {{-- ========== STATS ========== --}}
    <div class="wrap">
        <div class="stats">
            <div class="stats-row">
                <div class="stat">
                    <div class="num">4</div>
                    <div class="lbl">Role-based views</div>
                </div>
                <div class="stat">
                    <div class="num">8</div>
                    <div class="lbl">Core modules</div>
                </div>
                <div class="stat">
                    <div class="num">1</div>
                    <div class="lbl">Source of truth</div>
                </div>
                <div class="stat">
                    <div class="num">0</div>
                    <div class="lbl">Spreadsheets needed</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========== HOW IT WORKS ========== --}}
    <section class="section" id="how">
        <div class="wrap">
            <div class="section-head">
                <div class="section-eyebrow">How it works</div>
                <h2>Three steps from sign-up to a full workforce view.</h2>
                <p class="section-sub">No separate setup for each department — your role decides what you see the moment you're in.</p>
            </div>

            <div class="steps">
                <div class="stepcard" tabindex="0">
                    <div class="stepnum">01</div>
                    <h3>Create your account</h3>
                    <p>Sign up and verify with a one-time code. Your account is linked to your role by HR or an administrator.</p>
                </div>
                <div class="stepcard" tabindex="0">
                    <div class="stepnum">02</div>
                    <h3>Land on your dashboard</h3>
                    <p>Admin, HR, Unit Head, or Staff — you're routed straight to the view built for what you actually need to do.</p>
                </div>
                <div class="stepcard" tabindex="0">
                    <div class="stepnum">03</div>
                    <h3>Manage or request, in one place</h3>
                    <p>Update staff records, review leave, or submit a request yourself — all changes reflect across the system instantly.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ========== CTA ========== --}}
    <section class="cta-band">
        <div class="wrap">
            <h2>Bring your workforce into one place.</h2>
            <p>Create your account and get routed straight to the dashboard built for your role.</p>
            <div class="cta-actions">
                <a href="{{ route('signup') }}" class="btn btn-primary">Create an account</a>
                <a href="{{ route('login') }}" class="btn btn-ghost">I already have one</a>
            </div>
        </div>
    </section>

    {{-- ========== FOOTER ========== --}}
    <x-footbar />

</body>
</html>
