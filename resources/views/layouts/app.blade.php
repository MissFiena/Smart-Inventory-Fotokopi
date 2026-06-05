<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FOTOKOPI – @yield('title', 'Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
        [x-cloak] {
            display: none;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        /* SIDEBAR MENU */
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 14px;
            width: calc(100% - 28px);
            padding: 14px 18px;
            margin: 6px 14px;
            border-radius: 14px;
            color: #CBD5E1;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .sidebar-link:hover {
            background: rgba(255,255,255,0.08);
            color: white;
        }

        .sidebar-link.active {
            background: #4F46E5;
            color: white;
            box-shadow: 0 4px 12px rgba(79,70,229,0.35);
        }

        /* CARD STYLE */
        .card {
            background-color: #ffffff;
            border-radius: 1rem;
            border-width: 1px;
            border-color: #f3f4f6;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        /* GLOBAL FORMS ELEMENT SETTINGS */
        input[type="text"], input[type="number"], select, textarea {
            width: 100% !important;
            display: block !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            padding: 0.75rem 1rem !important;
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
            background-color: #ffffff !important;
            color: #111827 !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out !important;
        }
        input:focus, select:focus, textarea:focus {
            border-color: #4F46E5 !important;
            outline: 2px solid transparent !important;
            outline-offset: 2px !important;
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2) !important;
        }
    </style>
</head>

<body class="bg-softbg antialiased">

<div class="flex min-h-screen">

    <aside class="w-72 bg-sidebar text-white flex flex-col fixed h-screen shadow-2xl z-50">

        <div class="px-7 py-7 border-b border-white/10">
            <h1 class="text-4xl font-black tracking-wide">FOTOKOPI</h1>
            <p class="text-sm text-gray-400 mt-2">Smart Inventory System</p>
        </div>

        <nav class="flex-1 py-6 space-y-1 overflow-y-auto">
            <p class="text-gray-500 text-[11px] uppercase px-7 mb-3 tracking-[0.2em]">Main Menu</p>

            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="text-lg">🏠</span> Dashboard
            </a>

            <a href="{{ route('products.index') }}" class="sidebar-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <span class="text-lg">📦</span> Inventory
            </a>

            <a href="{{ route('checkin.form') }}" class="sidebar-link {{ request()->routeIs('checkin.*') ? 'active' : '' }}">
                <span class="text-lg">📥</span> Stock Check-In
            </a>

            <a href="{{ route('checkout.form') }}" class="sidebar-link {{ request()->routeIs('checkout.*') ? 'active' : '' }}">
                <span class="text-lg">📤</span> Stock Check-Out
            </a>

            <p class="text-gray-500 text-[11px] uppercase px-7 mt-8 mb-3 tracking-[0.2em]">Reports</p>

            @if(auth()->user()->isAdmin())
            <a href="{{ route('reports.index') }}" class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <span class="text-lg">📊</span> Reports
            </a>
            @endif

            <a href="{{ route('waste.index') }}" class="sidebar-link {{ request()->routeIs('waste.*') ? 'active' : '' }}">
                <span class="text-lg">🗑️</span> Waste / Loss
            </a>

            <a href="{{ route('alerts.index') }}" class="sidebar-link {{ request()->routeIs('alerts.*') ? 'active' : '' }}">
                <span class="text-lg">🔔</span> Alerts
            </a>
        </nav>

        <div class="p-5 border-t border-white/10">
            <div class="bg-white/5 rounded-2xl p-4 flex items-center gap-3">
                <div class="w-11 h-11 rounded-full bg-primary flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400 capitalize">{{ auth()->user()->role }}</p>
                </div>
            </div>
        </div>

    </aside>

    <div class="ml-72 flex-1 min-h-screen flex flex-col">

        <header class="bg-white border-b border-gray-200 px-8 py-5 flex items-center justify-between sticky top-0 z-40">
            <div>
                @if(request()->routeIs('dashboard'))
                    <h2 class="text-2xl font-bold text-gray-800">Hello, {{ auth()->user()->name }} 👋</h2>
                    <p class="text-sm text-gray-400 mt-1">Welcome back to your inventory dashboard</p>
                @else
                    <h2 class="text-2xl font-bold text-gray-800">@yield('page_title', 'Management Panel')</h2>
                    <p class="text-sm text-gray-400 mt-1">FOTOKOPI Smart Inventory System</p>
                @endif
            </div>

            <div class="flex items-center gap-5">
                <span class="text-sm text-gray-500">{{ now()->format('d F Y') }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="bg-red-500 hover:bg-red-600 text-white text-sm px-5 py-2.5 rounded-xl transition">
                        Logout
                    </button>
                </form>
            </div>
        </header>

        @if(session('success'))
        <div class="mx-8 mt-5 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm">
            ✅ {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mx-8 mt-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl shadow-sm">
            ❌ {{ session('error') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mx-8 mt-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl shadow-sm">
            @foreach($errors->all() as $error)
                <p class="text-sm">• {{ $error }}</p>
            @endforeach
        </div>
        @endif

        <main class="flex-1 p-8">
            @yield('content')
        </main>

    </div>

</div>

</body>
</html>