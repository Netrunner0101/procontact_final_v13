<!DOCTYPE html>
<html class="scroll-smooth" lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ProContact CRM | CRM & Planification pour Architectes d'Affaires</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .bento-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 1.5rem;
        }
        .executive-border {
            border: 1px solid rgba(132, 55, 40, 0.08);
        }
        .landing-nav {
            background: rgba(251, 249, 246, 0.80);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(197, 200, 185, 0.10);
        }
        .hero-gradient {
            background: linear-gradient(135deg, rgba(132,55,40,0.06) 0%, transparent 60%);
        }
        .landing-btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-fixed-dim));
            transition: all 0.25s;
        }
        .landing-btn-primary:hover {
            background: linear-gradient(135deg, #6d2a1d, var(--primary));
            box-shadow: 0 8px 24px rgba(132,55,40,0.25);
            transform: translateY(-2px);
        }
        .landing-btn-primary:active {
            transform: scale(0.97);
        }
        .landing-card {
            background: var(--surface-container-lowest);
            border-radius: 1rem;
            border: 1px solid rgba(132,55,40,0.08);
            box-shadow: 0 2px 6px rgba(27,28,26,0.03);
        }
        .landing-card-dark {
            background: var(--on-surface);
            color: var(--surface);
            border-radius: 1rem;
            box-shadow: 0 20px 40px rgba(27,28,26,0.15);
        }
        .landing-card-primary {
            background: var(--primary);
            color: var(--on-primary);
            border-radius: 1rem;
            box-shadow: 0 12px 24px rgba(132,55,40,0.2);
        }
        .cta-section {
            background: var(--primary);
            border-radius: 1.5rem;
            box-shadow: 0 20px 40px rgba(132,55,40,0.2);
        }
        .cta-dots {
            background-image: radial-gradient(rgba(255,255,255,0.15) 1px, transparent 1px);
            background-size: 30px 30px;
        }
    </style>
</head>
<body class="antialiased" style="font-family: 'Inter', sans-serif; background: var(--surface); color: var(--on-surface);">

    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 landing-nav h-20 flex items-center">
        <div class="flex justify-between items-center max-w-7xl mx-auto px-6 sm:px-8 w-full">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, var(--primary), var(--primary-fixed-dim));">
                    <span class="material-symbols-outlined text-white text-xl">architecture</span>
                </div>
                <div class="text-2xl font-bold tracking-tighter" style="font-family: 'Manrope', sans-serif; color: var(--on-surface);">
                    ProContact<span style="color: var(--primary);">.be</span>
                </div>
            </div>

            <div class="hidden md:flex items-center gap-10" style="font-family: 'Manrope', sans-serif; font-weight: 500; letter-spacing: -0.01em;">
                <a class="font-bold" style="color: var(--primary); border-bottom: 2px solid var(--primary); padding-bottom: 2px;" href="#features">Features</a>
                <a class="hover:opacity-80 transition-opacity" style="color: var(--on-surface-variant);" href="#architecture">Architecture</a>
                <a class="hover:opacity-80 transition-opacity" style="color: var(--on-surface-variant);" href="#specs">Spécifications</a>
            </div>

            <div class="flex items-center gap-4">
                @auth
                    @if(auth()->user()->role_id === 1)
                        <a href="{{ route('dashboard') }}" class="hidden md:block font-medium px-4 py-2 rounded-lg transition-all" style="color: var(--on-surface-variant);">Dashboard</a>
                    @else
                        <a href="{{ route('client.dashboard') }}" class="hidden md:block font-medium px-4 py-2 rounded-lg transition-all" style="color: var(--on-surface-variant);">Mon Espace</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="hidden md:block font-medium px-4 py-2 rounded-lg transition-all hover:opacity-80" style="color: var(--on-surface-variant);">Connexion</a>
                    <a href="{{ route('register') }}" class="landing-btn-primary text-white px-6 py-2.5 rounded-xl font-semibold" style="font-family: 'Manrope', sans-serif;">Démarrer</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="pt-20">

        <!-- Hero Section -->
        <section class="relative px-6 sm:px-8 pt-16 sm:pt-24 pb-24 sm:pb-32 overflow-hidden hero-gradient">
            <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                <div class="lg:col-span-7 z-10">
                    <div class="flex flex-wrap items-center gap-2 mb-6">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold tracking-widest uppercase" style="background: var(--primary-container); color: var(--on-primary-container); font-family: 'Inter', sans-serif;">Production-Ready</span>
                        <span class="font-bold text-xs" style="color: var(--primary);">Belgian Focus (.be)</span>
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-7xl font-extrabold leading-[1.05] tracking-tight mb-8" style="font-family: 'Manrope', sans-serif; color: var(--on-surface);">
                        CRM &amp; Planification pour <span style="color: var(--primary);">Architectes d'Affaires</span>
                    </h1>
                    <p class="text-lg sm:text-xl mb-10 max-w-2xl leading-relaxed" style="color: var(--on-surface-variant);">
                        Une architecture multi-tenant conçue pour les entreprises exigeantes. Orchestrez des workflows complexes avec une gestion de données haute densité, des performances Livewire en temps réel et une sécurité de grade industriel.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('register') }}" class="landing-btn-primary text-white px-8 sm:px-10 py-4 rounded-xl font-bold text-lg" style="font-family: 'Manrope', sans-serif;">
                            Commencer l'essai
                        </a>
                        <a href="#specs" class="px-8 sm:px-10 py-4 rounded-xl font-bold text-lg transition-all hover:opacity-80" style="font-family: 'Manrope', sans-serif; background: var(--surface-container-lowest); color: var(--on-surface); border: 1px solid rgba(197,200,185,0.30); box-shadow: var(--shadow-sm);">
                            Documentation technique
                        </a>
                    </div>
                    <div class="mt-12 flex flex-wrap items-center gap-6 text-sm font-medium" style="color: var(--on-surface-variant); opacity: 0.7;">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg" style="color: var(--primary);">check_circle</span>
                            Isolation Multi-tenant
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg" style="color: var(--primary);">check_circle</span>
                            Interface Temps Réel
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg" style="color: var(--primary);">check_circle</span>
                            Conformité RGPD
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-5 relative">
                    <div class="absolute inset-0 blur-3xl -z-10" style="background: linear-gradient(to top right, rgba(132,55,40,0.15), transparent);"></div>
                    <div class="rounded-2xl overflow-hidden transform lg:scale-110 lg:translate-x-12" style="background: var(--surface-container-highest); box-shadow: var(--shadow-xl); border: 1px solid rgba(197,200,185,0.10);">
                        <img alt="ProContact Tableau de Bord" class="w-full h-auto" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB65AT3N2fnt5NRtV3zYP2lA-uKcKIh644hQlLlecRc1tL3IITrRGs_T51oggmuxVwj1fFd6SH4-QhNfCvcQV03wigikrVmzYzIWxgeH2zuZgBSF_nB-YKOrLiLM-BJWXKN7oa0BSNM5zv9Ir3_zI0Z_xzopekSirpfuzZfx9AJwDX1DqMU1bad_ngiUyfi2w7VYnJw6rtJFr4ZrCAd236eeIN7C4hSvHOuDQyzygs4RlDyxZOWLwnpNrstekZrowQes-PUia7T0Q">
                    </div>
                </div>
            </div>
        </section>

        <!-- Feature Bento Grid -->
        <section id="features" class="py-24 sm:py-32" style="background: var(--surface-container-low);">
            <div class="max-w-7xl mx-auto px-6 sm:px-8">
                <div class="text-center mb-16 sm:mb-20">
                    <h2 class="text-3xl sm:text-4xl font-extrabold mb-6 tracking-tight" style="font-family: 'Manrope', sans-serif;">Opérations Sophistiquées. Zéro Latence.</h2>
                    <p class="text-lg max-w-2xl mx-auto" style="color: var(--on-surface-variant);">Construit sur un noyau de localisation francophone avec des standards de performance globaux.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">

                    <!-- Contact Lifecycle -->
                    <div class="md:col-span-4 md:row-span-2 landing-card p-8 flex flex-col">
                        <div class="w-12 h-12 rounded-lg flex items-center justify-center mb-6" style="background: rgba(132,55,40,0.10);">
                            <span class="material-symbols-outlined" style="color: var(--primary);">account_tree</span>
                        </div>
                        <h3 class="text-2xl font-bold mb-4" style="font-family: 'Manrope', sans-serif;">Cycle de Vie Contact</h3>
                        <p class="mb-8" style="color: var(--on-surface-variant);">Gérez vos leads à travers 8 statuts architecturés — de <span class="font-semibold" style="color: var(--primary);">Prospect</span> à <span class="font-semibold" style="color: var(--primary);">Fermé gagné</span>.</p>
                        <div class="mt-auto space-y-2">
                            <div class="flex items-center gap-3 p-3 rounded-lg" style="background: var(--surface); border: 1px solid rgba(197,200,185,0.10);">
                                <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                                <span class="text-sm font-medium">En attente</span>
                            </div>
                            <div class="flex items-center gap-3 p-3 rounded-lg" style="background: var(--surface); border: 1px solid rgba(197,200,185,0.10);">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                <span class="text-sm font-medium">Négociation</span>
                            </div>
                            <div class="flex items-center gap-3 p-3 rounded-lg" style="background: rgba(132,55,40,0.05); border: 1px solid rgba(132,55,40,0.20);">
                                <span class="w-2 h-2 rounded-full" style="background: var(--primary);"></span>
                                <span class="text-sm font-bold" style="color: var(--primary);">Fermé gagné</span>
                            </div>
                        </div>
                    </div>

                    <!-- Scheduling -->
                    <div class="md:col-span-5 landing-card p-8">
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background: rgba(138,110,46,0.10);">
                                <span class="material-symbols-outlined" style="color: var(--tertiary);">calendar_view_day</span>
                            </div>
                            <div class="px-3 py-1 text-[10px] font-bold uppercase tracking-widest rounded-full" style="background: rgba(138,110,46,0.05); color: var(--tertiary);">Double Vue</div>
                        </div>
                        <h3 class="text-2xl font-bold mb-2" style="font-family: 'Manrope', sans-serif;">Planification Automatisée</h3>
                        <p class="text-sm" style="color: var(--on-surface-variant);">Support multi-jours complet avec basculement entre vue Liste haute densité et vue Calendrier traditionnelle. Optimisé pour l'allocation de ressources complexes.</p>
                    </div>

                    <!-- Livewire UI -->
                    <div class="md:col-span-3 landing-card-dark p-8">
                        <div class="w-12 h-12 rounded-lg flex items-center justify-center mb-6" style="background: rgba(255,255,255,0.10);">
                            <span class="material-symbols-outlined">bolt</span>
                        </div>
                        <h3 class="text-xl font-bold mb-2" style="font-family: 'Manrope', sans-serif;">Interface Temps Réel</h3>
                        <p class="text-sm" style="opacity: 0.7;">Vivez l'expérience « application bureau » avec une interface 100% Livewire. Pas de rechargement, juste de la vitesse.</p>
                    </div>

                    <!-- Analytics -->
                    <div class="md:col-span-5 landing-card p-8">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background: rgba(68,72,62,0.10);">
                                <span class="material-symbols-outlined">analytics</span>
                            </div>
                            <div>
                                <div class="text-xs font-bold uppercase tracking-widest" style="color: var(--primary);">Rapports</div>
                                <div class="text-xl font-bold" style="font-family: 'Manrope', sans-serif;">Tendances 12 Mois</div>
                            </div>
                        </div>
                        <p class="text-sm mb-4" style="color: var(--on-surface-variant);">Intelligence approfondie avec export CSV pour toutes les métriques de performance et journaux d'activité.</p>
                        <div class="h-12 flex items-end gap-1 px-2">
                            <div class="w-full h-1/2 rounded-t-sm" style="background: rgba(132,55,40,0.20);"></div>
                            <div class="w-full h-3/4 rounded-t-sm" style="background: rgba(132,55,40,0.30);"></div>
                            <div class="w-full h-2/3 rounded-t-sm" style="background: rgba(132,55,40,0.40);"></div>
                            <div class="w-full h-full rounded-t-sm" style="background: var(--primary); box-shadow: 0 4px 12px rgba(132,55,40,0.20);"></div>
                            <div class="w-full h-1/2 rounded-t-sm" style="background: rgba(132,55,40,0.40);"></div>
                        </div>
                    </div>

                    <!-- Security -->
                    <div class="md:col-span-3 landing-card-primary p-8">
                        <h3 class="text-xl font-bold mb-4" style="font-family: 'Manrope', sans-serif;">Accès Fortifié</h3>
                        <ul class="space-y-3 text-sm font-medium" style="opacity: 0.9;">
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">key</span> Google/Apple OAuth
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">security</span> Rate Limiting
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">dns</span> Isolation des Données
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </section>

        <!-- Technical Specifications -->
        <section id="specs" class="py-24" style="background: var(--surface);">
            <div class="max-w-7xl mx-auto px-6 sm:px-8">
                <div class="mb-16">
                    <h2 class="text-3xl font-extrabold tracking-tight" style="font-family: 'Manrope', sans-serif;">Spécifications Techniques</h2>
                    <p style="color: var(--on-surface-variant);">Conçu pour la fiabilité, la transparence et la performance entreprise.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr style="border-bottom: 1px solid rgba(197,200,185,0.30);">
                                <th class="py-6 text-xs font-bold uppercase tracking-widest" style="font-family: 'Manrope', sans-serif; color: var(--on-surface-variant);">Catégorie</th>
                                <th class="py-6 text-xs font-bold uppercase tracking-widest" style="font-family: 'Manrope', sans-serif; color: var(--on-surface-variant);">Fonctionnalités</th>
                                <th class="py-6 text-xs font-bold uppercase tracking-widest text-right" style="font-family: 'Manrope', sans-serif; color: var(--on-surface-variant);">Standard</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid rgba(197,200,185,0.10);">
                                <td class="py-6 font-bold">Architecture</td>
                                <td class="py-6" style="color: var(--on-surface-variant);">Multi-tenant avec isolation stricte par entreprise & Portails Client Sécurisés</td>
                                <td class="py-6 text-right font-medium" style="color: var(--primary);">Entreprise</td>
                            </tr>
                            <tr style="border-bottom: 1px solid rgba(197,200,185,0.10);">
                                <td class="py-6 font-bold">Stack</td>
                                <td class="py-6" style="color: var(--on-surface-variant);">PostgreSQL, Redis, Docker-ready, déploiement CI/CD automatisé</td>
                                <td class="py-6 text-right font-medium">Production</td>
                            </tr>
                            <tr style="border-bottom: 1px solid rgba(197,200,185,0.10);">
                                <td class="py-6 font-bold">Localisation</td>
                                <td class="py-6" style="color: var(--on-surface-variant);">Interface French-first (FR), UTF-8 complet, conformité normes fiscales belges</td>
                                <td class="py-6 text-right font-medium">Natif</td>
                            </tr>
                            <tr>
                                <td class="py-6 font-bold">Interactivité</td>
                                <td class="py-6" style="color: var(--on-surface-variant);">Composants Livewire de grade bureau pour mises à jour instantanées</td>
                                <td class="py-6 text-right font-medium" style="color: var(--primary);">0ms Latence</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Final CTA -->
        <section id="architecture" class="py-24 sm:py-32 px-6 sm:px-8">
            <div class="max-w-5xl mx-auto cta-section p-10 sm:p-16 text-center relative overflow-hidden">
                <div class="absolute inset-0 cta-dots pointer-events-none"></div>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-extrabold mb-8 leading-tight relative z-10" style="font-family: 'Manrope', sans-serif; color: var(--on-primary);">
                    Architecturez l'avenir de votre entreprise.
                </h2>
                <p class="text-lg sm:text-xl mb-12 max-w-2xl mx-auto relative z-10" style="color: var(--primary-container); opacity: 0.9;">
                    Prêt à déployer ProContact pour votre équipe ? Rejoignez la plateforme CRM belge la plus sophistiquée.
                </p>
                <div class="flex flex-col sm:flex-row gap-6 justify-center relative z-10">
                    <a href="{{ route('register') }}" class="px-8 sm:px-10 py-5 rounded-xl font-extrabold text-lg sm:text-xl transition-all hover:opacity-90 active:scale-95" style="font-family: 'Manrope', sans-serif; background: var(--surface-container-lowest); color: var(--primary); box-shadow: var(--shadow-lg);">
                        Commencer l'essai gratuit
                    </a>
                    <a href="mailto:contact@procontact.be" class="px-8 sm:px-10 py-5 rounded-xl font-extrabold text-lg sm:text-xl transition-all hover:opacity-90" style="font-family: 'Manrope', sans-serif; background: rgba(132,55,40,0.7); color: var(--on-primary); border: 1px solid rgba(255,255,255,0.10);">
                        Contacter l'équipe
                    </a>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="w-full pt-16 pb-8 text-sm leading-relaxed" style="border-top: 1px solid rgba(197,200,185,0.10); background: var(--surface-container-low);">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 max-w-7xl mx-auto px-6 sm:px-8 mb-16">
            <div class="space-y-6">
                <div class="text-xl font-bold" style="font-family: 'Manrope', sans-serif; color: var(--on-surface);">
                    ProContact<span style="color: var(--primary);">.be</span>
                </div>
                <p class="max-w-xs" style="color: var(--on-surface-variant);">
                    Construire la fondation structurelle des relations d'affaires modernes avec une précision architecturale.
                </p>
            </div>
            <div>
                <h5 class="font-bold mb-6 uppercase tracking-widest text-xs" style="font-family: 'Manrope', sans-serif; color: var(--on-surface);">Architecture</h5>
                <ul class="space-y-4">
                    <li><a class="transition-opacity hover:opacity-80" href="#features" style="color: var(--on-surface-variant);">Composants Livewire</a></li>
                    <li><a class="transition-opacity hover:opacity-80" href="#features" style="color: var(--on-surface-variant);">Sécurité Multi-tenant</a></li>
                    <li><a class="transition-opacity hover:opacity-80" href="#specs" style="color: var(--on-surface-variant);">Intégration PostgreSQL</a></li>
                    <li><a class="transition-opacity hover:opacity-80" href="#specs" style="color: var(--on-surface-variant);">Docker/Déploiement</a></li>
                </ul>
            </div>
            <div>
                <h5 class="font-bold mb-6 uppercase tracking-widest text-xs" style="font-family: 'Manrope', sans-serif; color: var(--on-surface);">Solutions</h5>
                <ul class="space-y-4">
                    <li><a class="transition-opacity hover:opacity-80" href="#features" style="color: var(--on-surface-variant);">Planification</a></li>
                    <li><a class="transition-opacity hover:opacity-80" href="#features" style="color: var(--on-surface-variant);">Portails Client</a></li>
                    <li><a class="transition-opacity hover:opacity-80" href="#features" style="color: var(--on-surface-variant);">Analytique & CSV</a></li>
                    <li><a class="transition-opacity hover:opacity-80" href="#features" style="color: var(--on-surface-variant);">Gestion des Statuts</a></li>
                </ul>
            </div>
            <div>
                <h5 class="font-bold mb-6 uppercase tracking-widest text-xs" style="font-family: 'Manrope', sans-serif; color: var(--on-surface);">Connexion</h5>
                <div class="flex gap-4">
                    <a class="w-10 h-10 rounded-full flex items-center justify-center transition-all" href="#" style="background: var(--surface-container-high);">
                        <span class="material-symbols-outlined text-lg">public</span>
                    </a>
                    <a class="w-10 h-10 rounded-full flex items-center justify-center transition-all" href="#" style="background: var(--surface-container-high);">
                        <span class="material-symbols-outlined text-lg">terminal</span>
                    </a>
                </div>
                <div class="mt-6 text-xs font-medium" style="color: var(--on-surface-variant); opacity: 0.6;">Hébergé fièrement dans l'UE.</div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-6 sm:px-8 pt-8 flex flex-col md:flex-row justify-between items-center gap-4" style="border-top: 1px solid rgba(197,200,185,0.10);">
            <p style="color: var(--on-surface-variant); opacity: 0.7;">© {{ date('Y') }} ProContact CRM. Conçu pour les architectes d'affaires.</p>
            <div class="flex gap-8" style="color: var(--on-surface-variant); opacity: 0.7;">
                <a class="hover:opacity-80 transition-opacity" href="#">Confidentialité</a>
                <a class="hover:opacity-80 transition-opacity" href="#">CGU</a>
                <a class="hover:opacity-80 transition-opacity" href="#">Cookies</a>
            </div>
        </div>
    </footer>

</body>
</html>
