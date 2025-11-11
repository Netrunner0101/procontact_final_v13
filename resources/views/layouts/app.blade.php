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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body class="font-sans antialiased" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); min-height: 100vh;">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="glass-effect border-b border-gray-200/20 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform duration-200">
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
                            <a href="{{ route('contacts.manager') }}" class="nav-link {{ request()->routeIs('contacts.manager') ? 'nav-link-active' : '' }}">
                                <i class="fas fa-users"></i>
                                <span>Contacts</span>
                                <span class="nav-badge">Live</span>
                            </a>
                            <a href="{{ route('activites.index') }}" class="nav-link {{ request()->routeIs('activites.*') ? 'nav-link-active' : '' }}">
                                <i class="fas fa-briefcase"></i>
                                <span>Activités</span>
                            </a>
                            <a href="{{ route('appointments.manager') }}" class="nav-link {{ request()->routeIs('appointments.manager') ? 'nav-link-active' : '' }}">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Rendez-vous</span>
                                <span class="nav-badge">Live</span>
                            </a>
                            <a href="{{ route('rappels.index') }}" class="nav-link {{ request()->routeIs('rappels.*') ? 'nav-link-active' : '' }}">
                                <i class="fas fa-bell"></i>
                                <span>Rappels</span>
                            </a>
                            <a href="{{ route('notes.manager') }}" class="nav-link {{ request()->routeIs('notes.manager') ? 'nav-link-active' : '' }}">
                                <i class="fas fa-sticky-note"></i>
                                <span>Notes</span>
                                <span class="nav-badge">Live</span>
                            </a>
                            <a href="{{ route('statistics.dashboard') }}" class="nav-link {{ request()->routeIs('statistics.dashboard') ? 'nav-link-active' : '' }}">
                                <i class="fas fa-chart-bar"></i>
                                <span>Statistiques</span>
                                <span class="nav-badge">Live</span>
                            </a>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.clients.index') }}" class="nav-link {{ request()->routeIs('admin.clients.*') ? 'nav-link-active' : '' }}">
                                    <i class="fas fa-user-tie"></i>
                                    <span>Clients</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <div class="ml-3 relative">
                            @auth
                                <div class="relative inline-block text-left">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150" id="user-menu-button" onclick="toggleUserMenu()">
                                        <span>{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</span>
                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    
                                    <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                        <div class="px-4 py-2 text-sm text-gray-700 border-b">
                                            <div class="font-medium">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</div>
                                            <div class="text-gray-500">{{ Auth::user()->email }}</div>
                                            @if(Auth::user()->last_login_at)
                                                <div class="text-xs text-gray-400 mt-1">
                                                    Dernière connexion: {{ Auth::user()->last_login_at->diffForHumans() }}
                                                </div>
                                            @endif
                                        </div>
                                        <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-user mr-2"></i>
                                            Mon Profil
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}" class="block">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="fas fa-sign-out-alt mr-2"></i>
                                                Se déconnecter
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700">
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
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
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
            color: #6b7280;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
            position: relative;
        }
        
        .nav-link:hover {
            color: #374151;
            background-color: rgba(59, 130, 246, 0.1);
        }
        
        .nav-link-active {
            color: #2563eb;
            background-color: rgba(59, 130, 246, 0.1);
        }
        
        .nav-badge {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            font-size: 0.625rem;
            font-weight: 600;
            padding: 0.125rem 0.375rem;
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }
        
        .nav-link-active::after {
            content: '';
            position: absolute;
            bottom: -0.5rem;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 4px;
            background-color: #2563eb;
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
            border: 2px solid #f3f4f6;
            border-top: 2px solid #3b82f6;
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
        // Enhanced User Menu Toggle
        function toggleUserMenu() {
            const menu = document.getElementById('user-menu');
            menu.classList.toggle('hidden');
            
            // Add animation class
            if (!menu.classList.contains('hidden')) {
                menu.classList.add('fade-in-up');
            }
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('user-menu');
            const button = document.getElementById('user-menu-button');
            if (menu && button && !button.contains(event.target) && !menu.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });
        
        // Add loading state to forms
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        const originalText = submitBtn.textContent || submitBtn.value;
                        submitBtn.innerHTML = '<div class="spinner inline-block mr-2"></div>Chargement...';
                        
                        // Re-enable after 5 seconds as fallback
                        setTimeout(() => {
                            submitBtn.disabled = false;
                            submitBtn.textContent = originalText;
                        }, 5000);
                    }
                });
            });
            
            // Add fade-in animation to page content
            const main = document.querySelector('main');
            if (main) {
                main.classList.add('fade-in-up');
            }
        });
        
        // Enhanced notification system
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm fade-in-up ${
                type === 'success' ? 'bg-green-100 border border-green-400 text-green-700' :
                type === 'error' ? 'bg-red-100 border border-red-400 text-red-700' :
                'bg-blue-100 border border-blue-400 text-blue-700'
            }`;
            notification.innerHTML = `
                <div class="flex items-center justify-between">
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-current hover:opacity-70">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }
    </script>
    
    <!-- Livewire Scripts -->
    @livewireScripts
</body>
</html>
