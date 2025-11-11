<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Pro Contact') }} - Espace Client</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased" style="background: linear-gradient(135deg, #e0f2fe 0%, #b3e5fc 100%); min-height: 100vh;">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="glass-effect border-b border-blue-200/20 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user-circle text-white text-sm"></i>
                                </div>
                                <div>
                                    <h1 class="text-xl font-bold text-gradient">Pro Contact</h1>
                                    <span class="text-xs text-blue-600 font-medium">Espace Client</span>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden lg:flex lg:ml-10 lg:space-x-1">
                            <a href="{{ route('client.dashboard') }}" class="client-nav-link {{ request()->routeIs('client.dashboard') ? 'client-nav-link-active' : '' }}">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Tableau de bord</span>
                            </a>
                            <a href="{{ route('client.appointments') }}" class="client-nav-link {{ request()->routeIs('client.appointments*') ? 'client-nav-link-active' : '' }}">
                                <i class="fas fa-calendar-check"></i>
                                <span>Mes rendez-vous</span>
                            </a>
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <div class="ml-3 relative">
                            @auth
                                <div class="relative inline-block text-left">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150" id="client-user-menu-button" onclick="toggleClientUserMenu()">
                                        <span>{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</span>
                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    
                                    <div id="client-user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                        <div class="px-4 py-2 text-sm text-gray-700 border-b">
                                            <div class="font-medium">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</div>
                                            <div class="text-gray-500">{{ Auth::user()->email }}</div>
                                            <div class="text-xs text-blue-600 mt-1">Espace Client</div>
                                        </div>
                                        <form method="POST" action="{{ route('logout') }}" class="block">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                Se déconnecter
                                            </button>
                                        </form>
                                    </div>
                                </div>
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
        /* Client Navigation Styles */
        .client-nav-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #1e40af;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
            position: relative;
        }
        
        .client-nav-link:hover {
            color: #1d4ed8;
            background-color: rgba(59, 130, 246, 0.1);
        }
        
        .client-nav-link-active {
            color: #1d4ed8;
            background-color: rgba(59, 130, 246, 0.15);
            font-weight: 600;
        }
        
        .client-nav-link-active::after {
            content: '';
            position: absolute;
            bottom: -0.5rem;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 4px;
            background-color: #1d4ed8;
            border-radius: 50%;
        }
        
        /* Client Card Styles */
        .client-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid rgba(59, 130, 246, 0.1);
            transition: all 0.2s ease-in-out;
        }
        
        .client-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
        }
        
        /* Client Badge Styles */
        .client-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 500;
            border-radius: 9999px;
            gap: 0.25rem;
        }
        
        .client-badge-primary {
            background-color: rgba(59, 130, 246, 0.1);
            color: #1d4ed8;
        }
        
        .client-badge-success {
            background-color: rgba(34, 197, 94, 0.1);
            color: #15803d;
        }
        
        .client-badge-warning {
            background-color: rgba(245, 158, 11, 0.1);
            color: #d97706;
        }
        
        /* Client Button Styles */
        .client-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            text-decoration: none;
            gap: 0.5rem;
        }
        
        .client-btn-primary {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        
        .client-btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transform: translateY(-1px);
        }
        
        /* Mobile Responsive */
        @media (max-width: 1024px) {
            .client-nav-link span {
                display: none;
            }
            .client-nav-link {
                padding: 0.5rem;
                justify-content: center;
            }
        }
    </style>
    
    <script>
        // Enhanced Client User Menu Toggle
        function toggleClientUserMenu() {
            const menu = document.getElementById('client-user-menu');
            menu.classList.toggle('hidden');
            
            // Add animation class
            if (!menu.classList.contains('hidden')) {
                menu.classList.add('fade-in-up');
            }
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('client-user-menu');
            const button = document.getElementById('client-user-menu-button');
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
        
        // Client notification system
        function showClientNotification(message, type = 'info') {
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
</body>
</html>
