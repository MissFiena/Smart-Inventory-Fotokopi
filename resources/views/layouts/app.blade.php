<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FOTOKOPI – @yield('title', 'Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/jpeg" href="{{ asset('images/LOGO_FOTOKOPI.jpeg') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#4F46E5',
                    sidebar: '#0F172A',
                    softbg: '#F1F5F9',
                }
            }
        }
    }
    </script>

    <style>
        [x-cloak] { display: none; }
        body { font-family: 'Inter', sans-serif; }
        
        .sidebar-link {
            display: flex; align-items: center; gap: 14px;
            width: calc(100% - 28px); padding: 14px 18px;
            margin: 6px 14px; border-radius: 14px;
            color: #CBD5E1; font-size: 15px; font-weight: 500;
            transition: all 0.2s ease; white-space: nowrap;
        }
        .sidebar-link:hover { background: rgba(255,255,255,0.08); color: white; }
        .sidebar-link.active { background: #4F46E5; color: white; box-shadow: 0 4px 12px rgba(79,70,229,0.35); }
    </style>
</head>

<body class="bg-softbg antialiased">

<div class="flex min-h-screen">

    <aside class="w-72 bg-sidebar text-white flex flex-col fixed h-screen shadow-2xl z-50">
        <div class="px-7 py-7 border-b border-white/10 text-center">
            <div class="flex flex-col items-center gap-3">
                <img src="{{ asset('images/LOGO_FOTOKOPI.jpeg') }}" alt="Logo" class="h-24 w-auto rounded-lg">
                <h1 class="text-3xl font-black tracking-wide">FOTOKOPI</h1>
            </div>
            <p class="text-sm text-gray-400 mt-2">Smart Inventory System</p>
        </div>

        <nav class="flex-1 py-6 space-y-1 overflow-y-auto">
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">🏠 Dashboard</a>
            <a href="{{ route('products.index') }}" class="sidebar-link {{ request()->routeIs('products.*') ? 'active' : '' }}">📦 Inventory</a>
            <a href="{{ route('checkin.form') }}" class="sidebar-link {{ request()->routeIs('checkin.*') ? 'active' : '' }}">📥 Stock Check-In</a>
            <a href="{{ route('checkout.form') }}" class="sidebar-link {{ request()->routeIs('checkout.*') ? 'active' : '' }}">📤 Stock Check-Out</a>
            
            <p class="text-gray-500 text-[11px] uppercase px-7 mt-8 mb-3 tracking-[0.2em]">Reports</p>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('reports.index') }}" class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">📊 Reports</a>
            @endif
            <a href="{{ route('waste.index') }}" class="sidebar-link {{ request()->routeIs('waste.*') ? 'active' : '' }}">🗑️ Waste / Loss</a>
            <a href="{{ route('alerts.index') }}" class="sidebar-link {{ request()->routeIs('alerts.*') ? 'active' : '' }}">🔔 Alerts</a>
        </nav>
    </aside>

    <div class="ml-72 flex-1 min-h-screen flex flex-col">
        <header class="bg-white border-b border-gray-200 px-8 py-3 flex items-center justify-between sticky top-0 z-40">
            <div>
                @if(request()->routeIs('dashboard'))
                    <h2 class="text-2xl font-bold text-gray-800">Hello, {{ auth()->user()->name }} 👋</h2>
                @else
                    <h2 class="text-2xl font-bold text-gray-800 capitalize">@yield('page_title', 'Management Panel')</h2>
                @endif
                <p class="text-sm text-gray-400 mt-1">FOTOKOPI Smart Inventory System</p>
            </div>

            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-600 font-medium">{{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-sm px-5 py-2 rounded-xl transition">
                        Logout
                    </button>
                </form>
            </div>
        </header>

        <main class="flex-1 p-8">
            @yield('content')
        </main>
    </div>

</div>

</body>
</html>