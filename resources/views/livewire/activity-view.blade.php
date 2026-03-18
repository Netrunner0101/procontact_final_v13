<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header with Back Button -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 rounded-lg transition-colors" style="background: #f1f5f9; color: #475569;">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Retour
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold" style="color: #0f172a;">{{ $activity->nom }}</h1>
                        @if($activity->description)
                            <p class="mt-1" style="color: #64748b;">{{ $activity->description }}</p>
                        @endif
                    </div>
                </div>
                <a href="{{ route('activites.edit', $activity->id) }}" class="inline-flex items-center px-4 py-2 text-white rounded-lg transition-colors" style="background: #06b6d4;">
                    <i class="fas fa-edit mr-2"></i>
                    Modifier
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="rounded-lg shadow p-6" style="background: white; border: 1px solid #e2e8f0;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm" style="color: #64748b;">Contacts</p>
                        <p class="text-3xl font-bold" style="color: #06b6d4;">{{ $activity->contacts->count() }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background: #ecfeff;">
                        <i class="fas fa-users text-xl" style="color: #06b6d4;"></i>
                    </div>
                </div>
            </div>

            <div class="rounded-lg shadow p-6" style="background: white; border: 1px solid #e2e8f0;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm" style="color: #64748b;">Rendez-vous</p>
                        <p class="text-3xl font-bold" style="color: #10b981;">{{ $activity->rendezVous->count() }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background: #ecfdf5;">
                        <i class="fas fa-calendar-alt text-xl" style="color: #10b981;"></i>
                    </div>
                </div>
            </div>

            <div class="rounded-lg shadow p-6" style="background: white; border: 1px solid #e2e8f0;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm" style="color: #64748b;">Notes</p>
                        <p class="text-3xl font-bold" style="color: #8b5cf6;">{{ $activity->notes->count() ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background: #f5f3ff;">
                        <i class="fas fa-sticky-note text-xl" style="color: #8b5cf6;"></i>
                    </div>
                </div>
                <p class="text-xs mt-2" style="color: #94a3b8;">Accessible via rendez-vous</p>
            </div>

            <div class="rounded-lg shadow p-6" style="background: white; border: 1px solid #e2e8f0;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm" style="color: #64748b;">Statistiques</p>
                        <p class="text-3xl font-bold" style="color: #f59e0b;">{{ $activity->statistiques->count() ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background: #fffbeb;">
                        <i class="fas fa-chart-bar text-xl" style="color: #f59e0b;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="rounded-lg shadow mb-6" style="background: white; border: 1px solid #e2e8f0;">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <button wire:click="setActiveTab('overview')"
                            class="tab-button {{ $activeTab === 'overview' ? 'tab-active' : '' }}">
                        <i class="fas fa-home mr-2"></i>
                        Vue d'ensemble
                    </button>
                    <button wire:click="setActiveTab('contacts')"
                            class="tab-button {{ $activeTab === 'contacts' ? 'tab-active' : '' }}">
                        <i class="fas fa-users mr-2"></i>
                        Contacts
                    </button>
                    <button wire:click="setActiveTab('appointments')"
                            class="tab-button {{ $activeTab === 'appointments' ? 'tab-active' : '' }}">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Rendez-vous
                    </button>
                    <button wire:click="setActiveTab('statistics')"
                            class="tab-button {{ $activeTab === 'statistics' ? 'tab-active' : '' }}">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Statistiques
                    </button>
                </nav>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="rounded-lg shadow p-6" style="background: white; border: 1px solid #e2e8f0;">
            @if($activeTab === 'overview')
                <div>
                    <h2 class="text-2xl font-bold mb-6" style="color: #0f172a;">Vue d'ensemble</h2>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Recent Contacts -->
                        <div class="rounded-lg p-4" style="border: 1px solid #e2e8f0;">
                            <h3 class="font-semibold text-lg mb-4 flex items-center justify-between">
                                <span>Contacts r&eacute;cents</span>
                                <button wire:click="setActiveTab('contacts')" class="text-sm" style="color: #06b6d4;">
                                    Voir tout &rarr;
                                </button>
                            </h3>
                            @if($activity->contacts->take(5)->count() > 0)
                                <ul class="space-y-2">
                                    @foreach($activity->contacts->take(5) as $contact)
                                        <li class="flex items-center justify-between py-2 border-b last:border-0">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3" style="background: #ecfeff;">
                                                    <i class="fas fa-user" style="color: #06b6d4;"></i>
                                                </div>
                                                <div>
                                                    <p class="font-medium">{{ $contact->nom }} {{ $contact->prenom }}</p>
                                                    <p class="text-sm" style="color: #94a3b8;">{{ $contact->email }}</p>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-sm" style="color: #94a3b8;">Aucun contact</p>
                            @endif
                        </div>

                        <!-- Recent Appointments -->
                        <div class="rounded-lg p-4" style="border: 1px solid #e2e8f0;">
                            <h3 class="font-semibold text-lg mb-4 flex items-center justify-between">
                                <span>Prochains rendez-vous</span>
                                <button wire:click="setActiveTab('appointments')" class="text-sm" style="color: #06b6d4;">
                                    Voir tout &rarr;
                                </button>
                            </h3>
                            @if($activity->rendezVous->where('date_debut', '>=', now()->toDateString())->take(5)->count() > 0)
                                <ul class="space-y-2">
                                    @foreach($activity->rendezVous->where('date_debut', '>=', now()->toDateString())->sortBy('date_debut')->take(5) as $rdv)
                                        <li class="flex items-center justify-between py-2 border-b last:border-0">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3" style="background: #ecfdf5;">
                                                    <i class="fas fa-calendar" style="color: #10b981;"></i>
                                                </div>
                                                <div>
                                                    <p class="font-medium">{{ $rdv->titre }}</p>
                                                    <p class="text-sm" style="color: #94a3b8;">{{ \Carbon\Carbon::parse($rdv->date_debut)->format('d/m/Y') }} à {{ \Carbon\Carbon::parse($rdv->heure_debut)->format('H:i') }}</p>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-sm" style="color: #94a3b8;">Aucun rendez-vous à venir</p>
                            @endif
                        </div>
                    </div>

                    <!-- Activity Info -->
                    <div class="mt-6 rounded-lg p-4" style="border: 1px solid #e2e8f0;">
                        <h3 class="font-semibold text-lg mb-4">Informations de l'activit&eacute;</h3>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium" style="color: #94a3b8;">Date de cr&eacute;ation</dt>
                                <dd class="mt-1 text-sm" style="color: #0f172a;">{{ $activity->created_at->format('d/m/Y à H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium" style="color: #94a3b8;">Derni&egrave;re modification</dt>
                                <dd class="mt-1 text-sm" style="color: #0f172a;">{{ $activity->updated_at->format('d/m/Y à H:i') }}</dd>
                            </div>
                            @if($activity->description)
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium" style="color: #94a3b8;">Description</dt>
                                    <dd class="mt-1 text-sm" style="color: #0f172a;">{{ $activity->description }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>
            @elseif($activeTab === 'contacts')
                <div>
                    <livewire:contact-manager :activity-id="$activityId" :key="'contacts-'.$activityId" />
                </div>
            @elseif($activeTab === 'appointments')
                <div>
                    <livewire:appointment-manager :activity-id="$activityId" :key="'appointments-'.$activityId" />
                </div>
            @elseif($activeTab === 'statistics')
                <div>
                    <livewire:statistics-dashboard :activity-id="$activityId" :key="'statistics-'.$activityId" />
                </div>
            @endif
        </div>
    </div>

    <style>
        .tab-button {
            padding: 1rem 1.5rem;
            font-weight: 500;
            color: #6b7280;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
        }

        .tab-button:hover {
            color: #0891b2;
            border-bottom-color: #d1d5db;
        }

        .tab-active {
            color: #06b6d4 !important;
            border-bottom-color: #06b6d4 !important;
        }
    </style>
</div>
