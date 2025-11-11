<div class="dashboard-container">
    <!-- Header Section -->
    <div class="dashboard-header">
        <div class="welcome-section">
            <h1 class="dashboard-title">
                <i class="fas fa-tachometer-alt text-primary"></i>
                Bonjour, {{ Auth::user()->prenom ?? Auth::user()->nom }}
            </h1>
            <p class="dashboard-subtitle">
                @if($lastLogin)
                    Dernière connexion: {{ $lastLogin->format('d/m/Y à H:i') }}
                @else
                    Bienvenue dans votre espace de gestion
                @endif
            </p>
        </div>
        <div class="dashboard-actions">
            <button wire:click="refreshStats" class="btn btn-primary">
                <i class="fas fa-sync-alt"></i> Actualiser
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card gradient-blue" wire:click="$dispatch('navigate', {route: 'contacts.index'})">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['contacts'] ?? 0 }}</h3>
                <p class="stat-label">Contacts</p>
            </div>
        </div>

        <div class="stat-card gradient-green" wire:click="$dispatch('navigate', {route: 'rendez-vous.index'})">
            <div class="stat-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['appointments'] ?? 0 }}</h3>
                <p class="stat-label">Rendez-vous</p>
            </div>
        </div>

        <div class="stat-card gradient-purple" wire:click="$dispatch('navigate', {route: 'notes.index'})">
            <div class="stat-icon">
                <i class="fas fa-sticky-note"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['notes'] ?? 0 }}</h3>
                <p class="stat-label">Notes</p>
            </div>
        </div>

        <div class="stat-card gradient-orange" wire:click="$dispatch('navigate', {route: 'rappels.index'})">
            <div class="stat-icon">
                <i class="fas fa-bell"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['reminders'] ?? 0 }}</h3>
                <p class="stat-label">Rappels</p>
            </div>
        </div>

        <div class="stat-card gradient-teal" wire:click="$dispatch('navigate', {route: 'activites.index'})">
            <div class="stat-icon">
                <i class="fas fa-briefcase"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['activities'] ?? 0 }}</h3>
                <p class="stat-label">Activités</p>
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="dashboard-grid">
        <!-- Upcoming Appointments -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-calendar-check text-green-500"></i>
                    Prochains Rendez-vous
                </h3>
                <a href="{{ route('rendez-vous.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Nouveau
                </a>
            </div>
            <div class="card-body">
                @if($upcomingAppointments && count($upcomingAppointments) > 0)
                    <div class="appointment-list">
                        @foreach($upcomingAppointments as $appointment)
                            <div class="appointment-item" wire:key="appointment-{{ $appointment->id }}">
                                <div class="appointment-date">
                                    <div class="date-day">{{ $appointment->date_heure->format('d') }}</div>
                                    <div class="date-month">{{ $appointment->date_heure->format('M') }}</div>
                                </div>
                                <div class="appointment-details">
                                    <h4 class="appointment-title">{{ $appointment->contact->nom }} {{ $appointment->contact->prenom }}</h4>
                                    <p class="appointment-time">
                                        <i class="fas fa-clock"></i> {{ $appointment->date_heure->format('H:i') }}
                                    </p>
                                    <p class="appointment-activity">
                                        <i class="fas fa-briefcase"></i> {{ $appointment->activite->nom }}
                                    </p>
                                </div>
                                <div class="appointment-actions">
                                    <a href="{{ route('rendez-vous.show', $appointment) }}" class="btn btn-sm btn-outline">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-calendar-times text-gray-400"></i>
                        <p>Aucun rendez-vous à venir</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Contacts -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-plus text-blue-500"></i>
                    Contacts Récents
                </h3>
                <a href="{{ route('contacts.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Nouveau
                </a>
            </div>
            <div class="card-body">
                @if($recentContacts && count($recentContacts) > 0)
                    <div class="contact-list">
                        @foreach($recentContacts as $contact)
                            <div class="contact-item" wire:key="contact-{{ $contact->id }}">
                                <div class="contact-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="contact-details">
                                    <h4 class="contact-name">{{ $contact->nom }} {{ $contact->prenom }}</h4>
                                    <p class="contact-info">
                                        @if($contact->email)
                                            <i class="fas fa-envelope"></i> {{ $contact->email }}
                                        @endif
                                    </p>
                                    <p class="contact-date">
                                        <i class="fas fa-calendar"></i> {{ $contact->created_at->format('d/m/Y') }}
                                    </p>
                                </div>
                                <div class="contact-actions">
                                    <a href="{{ route('contacts.show', $contact) }}" class="btn btn-sm btn-outline">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-users text-gray-400"></i>
                        <p>Aucun contact récent</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Upcoming Reminders -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bell text-orange-500"></i>
                    Rappels à Venir
                </h3>
                <a href="{{ route('rappels.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Nouveau
                </a>
            </div>
            <div class="card-body">
                @if($upcomingReminders && count($upcomingReminders) > 0)
                    <div class="reminder-list">
                        @foreach($upcomingReminders as $reminder)
                            <div class="reminder-item" wire:key="reminder-{{ $reminder->id }}">
                                <div class="reminder-status">
                                    @php
                                        $isToday = $reminder->date_rappel->isToday();
                                        $isPast = $reminder->date_rappel->isPast();
                                    @endphp
                                    @if($isPast)
                                        <span class="status-badge status-expired">Expiré</span>
                                    @elseif($isToday)
                                        <span class="status-badge status-today">Aujourd'hui</span>
                                    @else
                                        <span class="status-badge status-upcoming">À venir</span>
                                    @endif
                                </div>
                                <div class="reminder-details">
                                    <h4 class="reminder-title">{{ $reminder->titre }}</h4>
                                    <p class="reminder-contact">
                                        <i class="fas fa-user"></i> {{ $reminder->rendezVous->contact->nom }} {{ $reminder->rendezVous->contact->prenom }}
                                    </p>
                                    <p class="reminder-date">
                                        <i class="fas fa-clock"></i> {{ $reminder->date_rappel->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                <div class="reminder-actions">
                                    <a href="{{ route('rappels.show', $reminder) }}" class="btn btn-sm btn-outline">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-bell-slash text-gray-400"></i>
                        <p>Aucun rappel à venir</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h3 class="section-title">
            <i class="fas fa-bolt"></i> Actions Rapides
        </h3>
        <div class="action-grid">
            <a href="{{ route('contacts.create') }}" class="action-card">
                <div class="action-icon gradient-blue">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="action-content">
                    <h4>Nouveau Contact</h4>
                    <p>Ajouter un contact</p>
                </div>
            </a>
            
            <a href="{{ route('rendez-vous.create') }}" class="action-card">
                <div class="action-icon gradient-green">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <div class="action-content">
                    <h4>Nouveau RDV</h4>
                    <p>Planifier un rendez-vous</p>
                </div>
            </a>
            
            <a href="{{ route('activites.create') }}" class="action-card">
                <div class="action-icon gradient-purple">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div class="action-content">
                    <h4>Nouvelle Activité</h4>
                    <p>Créer une activité</p>
                </div>
            </a>
            
            <a href="{{ route('statistiques.index') }}" class="action-card">
                <div class="action-icon gradient-orange">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div class="action-content">
                    <h4>Statistiques</h4>
                    <p>Voir les rapports</p>
                </div>
            </a>
        </div>
    </div>

<style>
.dashboard-container {
    padding: 1.5rem;
    max-width: 1400px;
    margin: 0 auto;
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 1rem;
    color: white;
}

.dashboard-title {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.dashboard-subtitle {
    margin: 0.5rem 0 0 0;
    opacity: 0.9;
    font-size: 1rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    padding: 1.5rem;
    border-radius: 1rem;
    color: white;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1);
}

.gradient-blue { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.gradient-green { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.gradient-purple { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.gradient-orange { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
.gradient-teal { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }

.stat-icon {
    font-size: 2rem;
    opacity: 0.9;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
    line-height: 1;
}

.stat-label {
    margin: 0.25rem 0 0 0;
    opacity: 0.9;
    font-size: 0.9rem;
}

.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.dashboard-card {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.card-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f9fafb;
}

.card-title {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.card-body {
    padding: 1.5rem;
    max-height: 400px;
    overflow-y: auto;
}

.appointment-list, .contact-list, .reminder-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.appointment-item, .contact-item, .reminder-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    transition: all 0.2s ease;
}

.appointment-item:hover, .contact-item:hover, .reminder-item:hover {
    border-color: #3b82f6;
    box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.1);
}

.appointment-date {
    text-align: center;
    min-width: 60px;
}

.date-day {
    font-size: 1.5rem;
    font-weight: 700;
    color: #3b82f6;
}

.date-month {
    font-size: 0.75rem;
    text-transform: uppercase;
    color: #6b7280;
}

.appointment-details, .contact-details, .reminder-details {
    flex: 1;
}

.appointment-title, .contact-name, .reminder-title {
    margin: 0 0 0.25rem 0;
    font-weight: 600;
    color: #1f2937;
}

.appointment-time, .appointment-activity, .contact-info, .contact-date, .reminder-contact, .reminder-date {
    margin: 0.25rem 0;
    font-size: 0.875rem;
    color: #6b7280;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.contact-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6b7280;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-expired { background: #fee2e2; color: #dc2626; }
.status-today { background: #fef3c7; color: #d97706; }
.status-upcoming { background: #dbeafe; color: #2563eb; }

.empty-state {
    text-align: center;
    padding: 2rem;
    color: #6b7280;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.quick-actions {
    margin-top: 2rem;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #1f2937;
}

.action-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.action-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: white;
    border-radius: 0.75rem;
    box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease;
    text-decoration: none;
    color: inherit;
}

.action-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.action-icon {
    width: 60px;
    height: 60px;
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.action-content h4 {
    margin: 0 0 0.25rem 0;
    font-weight: 600;
    color: #1f2937;
}

.action-content p {
    margin: 0;
    font-size: 0.875rem;
    color: #6b7280;
}

@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    }
    
    .action-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
    document.addEventListener('livewire:navigated', () => {
        // Handle navigation events
        Livewire.on('navigate', (event) => {
            window.location.href = route(event.route);
        });
    });
</script>
</div>
