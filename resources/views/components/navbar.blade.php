{{-- Navbar: brand far left; links + auth; hamburger on small screens --}}
<header>
    <div class="wrap nav">
        <a href="/" class="brand" aria-label="EN.AR Limited home">
            {{-- Custom logo mark: three rising figures (inspired by EN.AR brand) --}}
            <span class="logo-mark" aria-hidden="true">
                <svg viewBox="0 0 48 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="8" cy="8" r="3.2" class="logo-head logo-head-1"/>
                    <path class="logo-body logo-body-1" d="M8 13.5c-4.2 2.2-6.2 6.8-6.5 12.2" stroke-width="4.2" stroke-linecap="round"/>
                    <circle cx="22" cy="6.5" r="3.6" class="logo-head logo-head-2"/>
                    <path class="logo-body logo-body-2" d="M22 12.2c-4.8 2.6-7.4 7.6-7.8 14.2" stroke-width="4.6" stroke-linecap="round"/>
                    <circle cx="37.5" cy="5" r="4" class="logo-head logo-head-3"/>
                    <path class="logo-body logo-body-3" d="M37.5 11c-5.4 3-8.4 8.6-8.8 16.2" stroke-width="5" stroke-linecap="round"/>
                </svg>
            </span>
            <span class="logo-wordmark">
                <span class="logo-name">EN.AR</span>
                <span class="logo-sub">Limited</span>
            </span>
        </a>

        <button
            type="button"
            class="nav-toggle"
            aria-label="Open menu"
            aria-expanded="false"
            aria-controls="nav-menu"
        >
            <span class="nav-toggle-bar"></span>
            <span class="nav-toggle-bar"></span>
            <span class="nav-toggle-bar"></span>
        </button>

        <div class="nav-menu" id="nav-menu">
            <nav class="navlinks">
                <a href="#how">How it works</a>
                <a href="#features">About us</a>
                <a href="#solutions">Our solutions</a>
                <a href="#team">Our team</a>
                <a href="#contact">Contact us</a>
            </nav>

            <div class="nav-right">
                <a href="{{ route('login') }}" class="btn btn-login">Log in</a>
                <a href="{{ route('signup') }}" class="btn btn-primary">Sign up</a>
            </div>
        </div>
    </div>
</header>
