{{-- Navbar: brand far left, How it works link, Log in + Sign up far right --}}
<header>
    <div class="wrap nav">
        <a href="/" class="brand">
            <span class="mark">EA</span>
            EN·AR Workforce
        </a>

        <nav class="navlinks">
            <a href="#how">How it works</a>
        </nav>

        <div class="nav-right">
            <a href="{{ route('login') }}" class="login-link">Log in</a>
            <a href="{{ route('signup') }}" class="btn btn-primary">Sign up</a>
        </div>
    </div>
</header>
