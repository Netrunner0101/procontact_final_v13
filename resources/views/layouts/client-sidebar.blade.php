<!DOCTYPE html>
<html class="light" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'ProContact | Client Portal')</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&amp;family=Inter:wght@300;400;500;600&amp;display=swap" rel="stylesheet"/>
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
            "primary": "#A24E3D",
            "surface-container-low": "#f5f3f0",
            "on-primary-fixed": "#3e0400",
            "secondary-fixed": "#e4e2e1",
            "primary-fixed-dim": "#ffb4a5"
          },
          fontFamily: {
            "headline": ["Manrope", "sans-serif"],
            "body": ["Inter", "sans-serif"],
            "label": ["Inter", "sans-serif"]
          },
          borderRadius: {
            "DEFAULT": "0.125rem",
            "lg": "0.25rem",
            "xl": "0.5rem",
            "full": "0.75rem",
            "md": "0.375rem"
          },
        },
      },
    }
</script>
<style>
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        vertical-align: middle;
    }
    .glass-nav {
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }
    .font-manrope { font-family: 'Manrope', sans-serif; }
    body { font-family: 'Inter', sans-serif; }
    h1, h2, h3, .font-headline { font-family: 'Manrope', sans-serif; }
</style>
@yield('styles')
</head>
<body class="bg-background font-body text-on-surface selection:bg-primary-fixed selection:text-on-primary-fixed antialiased">
<!-- Sidebar Navigation — Client Portal (restricted) -->
<aside class="fixed inset-y-0 left-0 hidden md:flex flex-col h-screen w-64 border-r border-stone-200/50 bg-[#f5f3f0] font-manrope tracking-tight py-8 px-4 z-50">
<div class="mb-10 px-2">
<h1 class="text-2xl font-bold text-stone-900 tracking-tighter uppercase">ProContact</h1>
<p class="text-[10px] text-secondary mt-1 tracking-[0.2em] font-bold opacity-70">CLIENT PORTAL</p>
</div>
<nav class="flex-1 space-y-1">
<a class="flex items-center gap-3 px-3 py-2.5 rounded transition-all duration-200 @if(request()->routeIs('client.portal.dashboard')) text-[#A24E3D] font-bold border-r-4 border-[#A24E3D] bg-white scale-95 @else text-stone-500 hover:text-stone-900 hover:bg-stone-200/50 @endif" href="{{ route('client.portal.dashboard') }}">
<span class="material-symbols-outlined">dashboard</span>
<span class="text-sm">Dashboard</span>
</a>
<a class="flex items-center gap-3 px-3 py-2.5 rounded transition-all duration-200 @if(request()->routeIs('client.portal.appointments') || request()->routeIs('client.portal.appointment')) text-[#A24E3D] font-bold border-r-4 border-[#A24E3D] bg-white scale-95 @else text-stone-500 hover:text-stone-900 hover:bg-stone-200/50 @endif" href="{{ route('client.portal.appointments') }}">
<span class="material-symbols-outlined">event_available</span>
<span class="text-sm">My Appointments</span>
</a>
</nav>
<div class="mt-auto space-y-1">
<a class="flex items-center gap-3 px-3 py-2 rounded text-stone-500 hover:text-stone-900 transition-colors" href="#">
<span class="material-symbols-outlined">settings</span>
<span class="text-sm">Settings</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 rounded text-stone-500 hover:text-stone-900 transition-colors" href="#">
<span class="material-symbols-outlined">help_outline</span>
<span class="text-sm">Support</span>
</a>
@auth
<div class="mt-4 flex items-center gap-3 px-4 py-3 bg-white/40 rounded-xl">
<div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-sm">
    {{ strtoupper(substr(Auth::user()->prenom, 0, 1)) }}{{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
</div>
<div class="flex-1 overflow-hidden">
<p class="text-xs font-bold text-stone-900 truncate">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</p>
<p class="text-[10px] text-stone-500 uppercase tracking-widest">Client</p>
</div>
</div>
@endauth
</div>
</aside>

<!-- Top Navigation Bar -->
<header class="fixed top-0 right-0 w-full md:w-[calc(100%-16rem)] h-16 bg-[#fbf9f6]/80 backdrop-blur-xl border-b border-stone-200/30 flex justify-between items-center px-8 z-40 shadow-sm shadow-stone-900/5">
<div class="flex items-center gap-8">
<div class="relative group">
<span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-stone-400 text-lg">search</span>
<input class="bg-stone-100 border-none rounded-full pl-10 pr-4 py-1.5 text-sm w-64 focus:ring-1 focus:ring-primary/20 transition-all font-manrope" placeholder="Search appointments..." type="text"/>
</div>
<nav class="hidden lg:flex gap-8">
<a class="font-manrope text-sm font-semibold text-[#A24E3D]" href="#">Direct Dial</a>
<a class="font-manrope text-sm font-medium text-stone-500 hover:text-[#A24E3D] transition-all" href="{{ route('client.portal.dashboard') }}">Client Portal</a>
</nav>
</div>
<div class="flex items-center gap-4">
<button class="p-2 text-stone-400 hover:text-[#A24E3D] transition-colors">
<span class="material-symbols-outlined">notifications</span>
</button>
<button class="p-2 text-stone-400 hover:text-[#A24E3D] transition-colors">
<span class="material-symbols-outlined">history</span>
</button>
<div class="h-6 w-px bg-stone-200 mx-2"></div>
@auth
<div class="flex items-center gap-3 pl-2">
<div class="text-right hidden sm:block">
<p class="text-xs font-bold text-on-surface leading-tight font-manrope">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</p>
<p class="text-[10px] text-secondary font-medium uppercase tracking-wider leading-tight">Client Portal</p>
</div>
<div class="h-9 w-9 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs ring-2 ring-primary/10">
    {{ strtoupper(substr(Auth::user()->prenom, 0, 1)) }}{{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
</div>
</div>
@endauth
</div>
</header>

<!-- Main Content -->
<main class="pt-24 pb-12 md:ml-64 px-4 sm:px-8 lg:px-12 max-w-[1440px] mx-auto">
@if (session('success'))
<div class="mb-6 flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium bg-green-50 text-green-800 border border-green-200">
<span class="material-symbols-outlined text-lg">check_circle</span>
{{ session('success') }}
</div>
@endif

@if (session('error'))
<div class="mb-6 flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium bg-red-50 text-red-800 border border-red-200">
<span class="material-symbols-outlined text-lg">error</span>
{{ session('error') }}
</div>
@endif

@yield('content')
</main>

<!-- Mobile Bottom Navigation -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-stone-100 h-20 flex items-center justify-around px-4 z-50 glass-nav shadow-inner">
<a class="flex flex-col items-center @if(request()->routeIs('client.portal.dashboard')) text-primary @else text-stone-400 @endif" href="{{ route('client.portal.dashboard') }}">
<span class="material-symbols-outlined" @if(request()->routeIs('client.portal.dashboard')) style="font-variation-settings: 'FILL' 1;" @endif>dashboard</span>
<span class="text-[9px] font-bold uppercase tracking-widest mt-1">Home</span>
</a>
<a class="flex flex-col items-center @if(request()->routeIs('client.portal.appointments') || request()->routeIs('client.portal.appointment')) text-primary @else text-stone-400 @endif" href="{{ route('client.portal.appointments') }}">
<span class="material-symbols-outlined" @if(request()->routeIs('client.portal.appointments')) style="font-variation-settings: 'FILL' 1;" @endif>event</span>
<span class="text-[9px] font-bold uppercase tracking-widest mt-1">Calendar</span>
</a>
<a class="flex flex-col items-center text-stone-400" href="#">
<span class="material-symbols-outlined">account_circle</span>
<span class="text-[9px] font-bold uppercase tracking-widest mt-1">Profile</span>
</a>
</nav>
@yield('scripts')
</body>
</html>
