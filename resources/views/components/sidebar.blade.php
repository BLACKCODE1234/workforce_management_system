{{-- Sidebar: navigation for staff dashboard --}}
<aside class="sidebar">
    <a href="{{ route('dashboard.staff') }}" class="sidebar-brand">EN.AR</a>
    <ul class="sidebar-nav">
        <li><a href="{{ route('dashboard.staff') }}" class="is-active">Dashboard</a></li>
        <li><a href="{{ route('leave.index') }}">My leave</a></li>
        <li><a href="{{ route('leave.create') }}">Request leave</a></li>
        <li><a href="{{ route('profile.edit') }}">Profile</a></li>
    </ul>
</aside>
