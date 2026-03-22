<!DOCTYPE html>
<html class="light" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'ProContact CRM')</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&amp;family=Manrope:wght@300;400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "inverse-on-surface": "#f2f0ed",
            "secondary-fixed-dim": "#c8c6c5",
            "surface-container-lowest": "#ffffff",
            "on-secondary-fixed-variant": "#474747",
            "background": "#fbf9f6",
            "tertiary-container": "#816221",
            "surface": "#fbf9f6",
            "on-tertiary-container": "#ffe3b2",
            "on-secondary": "#ffffff",
            "surface-container-highest": "#e4e2df",
            "error": "#ba1a1a",
            "inverse-primary": "#ffb4a5",
            "on-surface-variant": "#55433f",
            "on-primary-container": "#ffdfd9",
            "tertiary-fixed-dim": "#e9c176",
            "on-error-container": "#93000a",
            "on-secondary-container": "#656464",
            "surface-tint": "#974635",
            "on-tertiary": "#ffffff",
            "tertiary": "#664a09",
            "secondary": "#5f5e5e",
            "on-primary": "#ffffff",
            "secondary-container": "#e4e2e1",
            "surface-variant": "#e4e2df",
            "primary-container": "#a24e3d",
            "outline-variant": "#dbc1bc",
            "tertiary-fixed": "#ffdea5",
            "on-background": "#1b1c1a",
            "surface-container-high": "#eae8e5",
            "surface-container": "#efeeeb",
            "surface-bright": "#fbf9f6",
            "on-primary-fixed-variant": "#792f21",
            "error-container": "#ffdad6",
            "surface-dim": "#dbdad7",
            "on-tertiary-fixed-variant": "#5d4201",
            "on-secondary-fixed": "#1b1c1c",
            "on-error": "#ffffff",
            "outline": "#88726e",
            "on-surface": "#1b1c1a",
            "inverse-surface": "#30312f",
            "on-tertiary-fixed": "#261900",
            "primary-fixed": "#ffdad3",
            "primary": "#843728",
            "surface-container-low": "#f5f3f0",
            "on-primary-fixed": "#3e0400",
            "secondary-fixed": "#e4e2e1",
            "primary-fixed-dim": "#ffb4a5"
          },
          fontFamily: {
            "headline": ["Manrope"],
            "body": ["Inter"],
            "label": ["Inter"]
          },
          borderRadius: {"DEFAULT": "0.125rem", "lg": "0.25rem", "xl": "0.5rem", "full": "0.75rem"},
        },
      },
    }
</script>
<style>
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        vertical-align: middle;
    }
    body { font-family: 'Inter', sans-serif; }
    h1, h2, h3, .font-headline { font-family: 'Manrope', sans-serif; }
    .glass-nav {
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }
</style>
@yield('styles')
</head>
<body class="bg-background text-on-surface selection:bg-primary/20 antialiased">
<div class="flex min-h-screen">
<!-- Sidebar Navigation -->
<aside class="fixed inset-y-0 left-0 z-50 hidden md:flex flex-col h-screen w-64 border-r border-stone-200/50 bg-[#f5f3f0] font-['Manrope'] antialiased tracking-tight py-8 px-4">
<div class="mb-10 px-2">
<span class="text-2xl font-bold text-stone-900 tracking-tighter uppercase">ProContact</span>
<p class="text-[10px] tracking-[0.2em] uppercase text-stone-400 mt-1">CRM Architect</p>
</div>
<nav class="flex-1 space-y-1">
<a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 @if(request()->routeIs('executive.dashboard')) text-[#843728] font-bold border-r-4 border-[#843728] bg-white/50 @else text-stone-500 hover:text-stone-900 hover:bg-stone-200/50 @endif" href="{{ route('executive.dashboard') }}">
<span class="material-symbols-outlined text-[20px]">dashboard</span>
<span class="text-sm">Dashboard</span>
</a>
<a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-stone-500 hover:text-stone-900 hover:bg-stone-200/50 transition-all duration-200" href="#">
<span class="material-symbols-outlined text-[20px]">business</span>
<span class="text-sm">Accounts</span>
</a>
<a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 @if(request()->routeIs('executive.contacts')) text-[#843728] font-bold border-r-4 border-[#843728] bg-white/50 @else text-stone-500 hover:text-stone-900 hover:bg-stone-200/50 @endif" href="{{ route('executive.contacts') }}">
<span class="material-symbols-outlined text-[20px]">groups</span>
<span class="text-sm">Contacts</span>
</a>
<a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-stone-500 hover:text-stone-900 hover:bg-stone-200/50 transition-all duration-200" href="#">
<span class="material-symbols-outlined text-[20px]">leaderboard</span>
<span class="text-sm">Leads</span>
</a>
<a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-stone-500 hover:text-stone-900 hover:bg-stone-200/50 transition-all duration-200" href="#">
<span class="material-symbols-outlined text-[20px]">handshake</span>
<span class="text-sm">Deals</span>
</a>
<a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 @if(request()->routeIs('executive.scheduler')) text-[#843728] font-bold border-r-4 border-[#843728] bg-white/50 @else text-stone-500 hover:text-stone-900 hover:bg-stone-200/50 @endif" href="{{ route('executive.scheduler') }}">
<span class="material-symbols-outlined text-[20px]">event_available</span>
<span class="text-sm">Scheduler</span>
</a>
<a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-stone-500 hover:text-stone-900 hover:bg-stone-200/50 transition-all duration-200" href="#">
<span class="material-symbols-outlined text-[20px]">insights</span>
<span class="text-sm">Analytics</span>
</a>
</nav>
<div class="mt-auto space-y-1">
<button class="w-full mb-6 py-3 px-4 rounded-lg bg-[#843728] text-white font-semibold text-sm flex items-center justify-center gap-2 hover:opacity-90 transition-all">
<span class="material-symbols-outlined text-[18px]">add</span>
    New Record
</button>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-stone-500 hover:text-stone-900 hover:bg-stone-200/50 transition-all" href="#">
<span class="material-symbols-outlined text-[20px]">settings</span>
<span class="text-sm">Settings</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-stone-500 hover:text-stone-900 hover:bg-stone-200/50 transition-all" href="#">
<span class="material-symbols-outlined text-[20px]">help_outline</span>
<span class="text-sm">Support</span>
</a>
@auth
<div class="mt-4 flex items-center gap-3 px-4 py-3 bg-white/40 rounded-xl">
<div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-sm">
    {{ strtoupper(substr(Auth::user()->prenom, 0, 1)) }}{{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
</div>
<div class="flex-1 overflow-hidden">
<p class="text-xs font-bold text-stone-900 truncate">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</p>
<p class="text-[10px] text-stone-500 uppercase tracking-widest">{{ Auth::user()->isAdmin() ? 'Admin' : 'Client' }}</p>
</div>
</div>
@endauth
</div>
</aside>

<!-- Main Content -->
<main class="md:ml-64 flex-1 min-w-0 overflow-auto bg-surface relative">
<!-- Top Navigation Bar -->
<header class="sticky top-0 right-0 w-full h-16 glass-nav bg-[#fbf9f6]/80 border-b border-stone-200/10 shadow-sm shadow-stone-900/5 flex justify-between items-center px-8 z-40 font-['Manrope'] text-sm font-medium">
<div class="flex items-center gap-6 flex-1">
<div class="relative w-64 group">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-stone-400 text-[18px]">search</span>
<input class="w-full pl-10 pr-4 py-1.5 bg-surface-container-low border-none rounded-full text-xs focus:ring-1 focus:ring-[#843728]/20 transition-all outline-none placeholder:text-stone-400" placeholder="Global Search" type="text"/>
</div>
<a class="text-[#843728] font-semibold flex items-center gap-1" href="#">
<span class="material-symbols-outlined text-[18px]">call</span>
    Direct Dial
</a>
@yield('top-nav-links')
</div>
<div class="flex items-center gap-5">
<button class="text-stone-600 hover:text-[#843728] transition-all">
<span class="material-symbols-outlined text-[20px]">notifications</span>
</button>
<button class="text-stone-600 hover:text-[#843728] transition-all">
<span class="material-symbols-outlined text-[20px]">history</span>
</button>
<button class="text-stone-600 hover:text-[#843728] transition-all">
<span class="material-symbols-outlined text-[20px]">help</span>
</button>
<div class="h-8 w-px bg-stone-200 mx-2"></div>
@auth
<div class="flex items-center gap-3 cursor-pointer group">
<div class="text-right hidden sm:block">
<p class="text-xs font-bold leading-none">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</p>
<p class="text-[10px] text-stone-500 leading-tight">{{ Auth::user()->isAdmin() ? 'Admin Level' : 'Client' }}</p>
</div>
<div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs border border-stone-200/50">
    {{ strtoupper(substr(Auth::user()->prenom, 0, 1)) }}{{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
</div>
</div>
@endauth
</div>
</header>

@yield('content')
</main>
</div>
@yield('scripts')
</body>
</html>
