<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Pro Contact')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="sticky top-0 z-50" style="background: #0f172a; border-bottom: 1px solid rgba(148,163,184,0.1);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                                <div class="w-9 h-9 rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform duration-200" style="background: linear-gradient(135deg, #06b6d4, #8b5cf6);">
                                    <i class="fas fa-address-book text-white text-sm"></i>
                                </div>
                                <span class="text-xl font-bold text-gradient">Pro Contact</span>
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden lg:flex lg:ml-10 lg:space-x-1">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'nav-link-active' : '' }}">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('activites.index') }}" class="nav-link {{ request()->routeIs('activites.*') ? 'nav-link-active' : '' }}">
                                <i class="fas fa-briefcase"></i>
                                <span>Activit&eacute;s</span>
                            </a>
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <div class="ml-3 relative">
                            @auth
                                <div class="relative inline-block text-left">
                                    <button type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200" id="user-menu-button" onclick="toggleUserMenu()" style="background: rgba(148,163,184,0.1); border: 1px solid rgba(148,163,184,0.15); color: #e2e8f0;">
                                        <div class="w-7 h-7 rounded-full flex items-center justify-center mr-2" style="background: linear-gradient(135deg, #06b6d4, #8b5cf6);">
                                            <i class="fas fa-user text-white text-xs"></i>
                                        </div>
                                        <span>{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</span>
                                        <svg class="ml-2 -mr-0.5 h-4 w-4 opacity-60" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>

                                    <div id="user-menu" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-slate-200 py-1 z-50">
                                        <div class="px-4 py-3 border-b border-slate-100">
                                            <div class="font-semibold text-slate-800">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</div>
                                            <div class="text-sm text-slate-500 mt-0.5">{{ Auth::user()->email }}</div>
                                            @if(Auth::user()->last_login_at)
                                                <div class="text-xs text-slate-400 mt-1">
                                                    Derni&egrave;re connexion: {{ Auth::user()->last_login_at->diffForHumans() }}
                                                </div>
                                            @endif
                                        </div>
                                        <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-2.5 text-sm text-slate-700 hover:bg-cyan-50 hover:text-cyan-700 transition-colors">
                                            <i class="fas fa-user mr-3 text-slate-400"></i>
                                            Mon Profil
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}" class="block">
                                            @csrf
                                            <button type="submit" class="flex items-center w-full text-left px-4 py-2.5 text-sm text-slate-700 hover:bg-rose-50 hover:text-rose-600 transition-colors">
                                                <i class="fas fa-sign-out-alt mr-3 text-slate-400"></i>
                                                Se d&eacute;connecter
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('login') }}" class="text-slate-300 hover:text-white transition-colors">
                                    Se connecter
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            @if (session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                    <div class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium" style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0;">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                    <div class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium" style="background: #fff1f2; color: #e11d48; border: 1px solid #fecdd3;">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <style>
        /* Navigation Link Styles */
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #94a3b8;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
            position: relative;
        }

        .nav-link:hover {
            color: #e2e8f0;
            background-color: rgba(148, 163, 184, 0.1);
        }

        .nav-link-active {
            color: #22d3ee !important;
            background-color: rgba(6, 182, 212, 0.1);
        }

        .nav-link-active::after {
            content: '';
            position: absolute;
            bottom: -0.5rem;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 4px;
            background-color: #06b6d4;
            border-radius: 50%;
        }

        /* Mobile Navigation */
        @media (max-width: 1024px) {
            .nav-link span {
                display: none;
            }
            .nav-link {
                padding: 0.5rem;
                justify-content: center;
            }
        }

        /* Enhanced Animations */
        .fade-in-up {
            animation: fadeInUp 0.3s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Loading Spinner */
        .spinner {
            border: 2px solid #e2e8f0;
            border-top: 2px solid #06b6d4;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <script>
        function toggleUserMenu() {
            const menu = document.getElementById('user-menu');
            menu.classList.toggle('hidden');
            if (!menu.classList.contains('hidden')) {
                menu.classList.add('fade-in-up');
            }
        }

        document.addEventListener('click', function(event) {
            const menu = document.getElementById('user-menu');
            const button = document.getElementById('user-menu-button');
            if (menu && button && !button.contains(event.target) && !menu.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        const originalText = submitBtn.textContent || submitBtn.value;
                        submitBtn.innerHTML = '<div class="spinner inline-block mr-2"></div>Chargement...';
                        setTimeout(() => {
                            submitBtn.disabled = false;
                            submitBtn.textContent = originalText;
                        }, 5000);
                    }
                });
            });

            const main = document.querySelector('main');
            if (main) {
                main.classList.add('fade-in-up');
            }
        });

        function showNotification(message, type = 'success') {
            const colors = {
                success: 'background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0;',
                error: 'background: #fff1f2; color: #e11d48; border: 1px solid #fecdd3;',
                info: 'background: #ecfeff; color: #0891b2; border: 1px solid #a5f3fc;'
            };
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 z-50 px-4 py-3 rounded-xl shadow-lg max-w-sm fade-in-up text-sm font-medium';
            notification.style.cssText = colors[type] || colors.info;
            notification.innerHTML = `
                <div class="flex items-center justify-between gap-3">
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="opacity-60 hover:opacity-100">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            document.body.appendChild(notification);
            setTimeout(() => { if (notification.parentElement) notification.remove(); }, 5000);
        }
    </script>

    @livewireScripts
</body>
</html>
