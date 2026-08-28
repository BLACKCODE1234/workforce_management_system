{{-- Sidebar: navigation for staff dashboard --}}
<aside class="sidebar" id="dashboard-sidebar">
    <div class="sidebar-head">
        <a href="{{ route('dashboard.staff') }}" class="sidebar-brand">EN.AR</a>
        <button class="dashboard-toggle" id="dashboard-toggle" aria-expanded="false" aria-controls="dashboard-nav" aria-label="Open menu">
            <span></span><span></span><span></span>
        </button>
    </div>
    <ul class="sidebar-nav" id="dashboard-nav">
        <li><a href="{{ route('dashboard.staff') }}" class="is-active">Dashboard</a></li>
        <li><a href="{{ route('leave.index') }}">My leave</a></li>
        <li><a href="{{ route('leave.create') }}">Request leave</a></li>
        <li><a href="{{ route('profile.edit') }}">Profile</a></li>
        <li class="sidebar-nav-logout">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </li>
    </ul>
    <div class="sidebar-logout">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
    <div class="sidebar-backdrop" id="dashboard-backdrop"></div>
    <x-footbar />
</aside>
