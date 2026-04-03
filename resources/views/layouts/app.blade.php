<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MilkBook') — MilkBook</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

{{-- ── Sidebar ──────────────────────────────────────────────────────────────── --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <span class="sidebar-logo">🥛</span>
        <span class="sidebar-brand">MilkBook</span>
        <button class="sidebar-close" onclick="toggleSidebar()">✕</button>
    </div>

    <div class="sidebar-user">
        <div class="sidebar-avatar">{{ strtoupper(substr(session('user')['name'] ?? '?', 0, 1)) }}</div>
        <div>
            <div class="sidebar-username">{{ session('user')['name'] ?? '?' }}</div>
            <div class="sidebar-phone">{{ session('user')['phone'] ?? '' }}</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ route('dashboard') }}"   class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="nav-icon">🏠</span> Dashboard
        </a>
        <a href="{{ route('customers.index') }}" class="nav-item {{ request()->routeIs('customers.*') ? 'active' : '' }}">
            <span class="nav-icon">👥</span> Customers
        </a>
        <a href="{{ route('profile') }}" class="nav-item {{ request()->routeIs('profile') ? 'active' : '' }}">
            <span class="nav-icon">👤</span> My Profile
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">🚪 Logout</button>
        </form>
    </div>
</aside>
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

{{-- ── Main ─────────────────────────────────────────────────────────────────── --}}
<div class="main-wrap">
    <header class="topbar">
        <button class="topbar-menu" onclick="toggleSidebar()">☰</button>
        <span class="topbar-title">@yield('topbar-title', 'MilkBook')</span>
        @yield('topbar-actions')
    </header>

    <main class="content">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('show');
}
</script>
</body>
</html>
