<div class="statistics-dashboard">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="header-content">
            <h1 class="page-title">
                <i class="fas fa-chart-bar"></i>
                Tableau de Bord Statistiques
            </h1>
            <p class="page-subtitle">Analysez vos performances et tendances</p>
        </div>
        <div class="header-actions">
            <button wire:click="exportData('global')" class="btn btn-secondary">
                <i class="fas fa-download"></i> Exporter CSV
            </button>
            <button wire:click="loadStatistics" class="btn btn-primary">
                <i class="fas fa-sync-alt"></i> Actualiser
            </button>
        </div>
    </div>

    <!-- Controls -->
    <div class="controls-section">
        <div class="control-group">
            <label for="dateRange" class="control-label">Période d'analyse</label>
            <select id="dateRange" wire:model.live="dateRange" class="control-select">
                <option value="3">3 derniers mois</option>
                <option value="6">6 derniers mois</option>
                <option value="12">12 derniers mois</option>
                <option value="24">24 derniers mois</option>
            </select>
        </div>
        
        <div class="control-group">
            <label for="selectedActivity" class="control-label">Activité spécifique</label>
            <select id="selectedActivity" wire:model.live="selectedActivity" class="control-select">
                <option value="">Toutes les activités</option>
                @foreach($activities as $activity)
                    <option value="{{ $activity->id }}">{{ $activity->nom }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Monthly Trends Chart -->
    <div class="chart-section">
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">
                    <i class="fas fa-line-chart"></i>
                    Évolution Mensuelle
                </h3>
            </div>
            <div class="chart-container">
                <canvas id="monthlyTrendsChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="stats-grid">
        <!-- Activity Performance -->
        <div class="stat-card">
            <div class="stat-header">
                <h3 class="stat-title">
                    <i class="fas fa-briefcase"></i>
                    Performance par Activité
                </h3>
            </div>
            <div class="stat-content">
                @if($activityStats && count($activityStats) > 0)
                    <div class="activity-list">
                        @foreach($activityStats as $activity)
                            <div class="activity-item">
                                <div class="activity-info">
                                    <h4 class="activity-name">{{ $activity->nom }}</h4>
                                    <p class="activity-description">{{ $activity->description }}</p>
                                </div>
                                <div class="activity-stats">
                                    <span class="stat-number">{{ $activity->rendez_vous_count }}</span>
                                    <span class="stat-label">RDV</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-briefcase"></i>
                        <p>Aucune activité trouvée</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Status Distribution -->
        <div class="stat-card">
            <div class="stat-header">
                <h3 class="stat-title">
                    <i class="fas fa-chart-pie"></i>
                    Répartition des Statuts
                </h3>
            </div>
            <div class="stat-content">
                @if($statusDistribution && count($statusDistribution) > 0)
                    <div class="status-list">
                        @foreach($statusDistribution as $status)
                            <div class="status-item">
                                <div class="status-info">
                                    <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $status->statut)) }}">
                                        {{ $status->statut }}
                                    </span>
                                </div>
                                <div class="status-count">
                                    {{ $status->count }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="chart-container small">
                        <canvas id="statusChart" width="200" height="200"></canvas>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-chart-pie"></i>
                        <p>Aucune donnée de statut</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Priority Distribution -->
        <div class="stat-card">
            <div class="stat-header">
                <h3 class="stat-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    Répartition des Priorités
                </h3>
            </div>
            <div class="stat-content">
                @if($priorityDistribution && count($priorityDistribution) > 0)
                    <div class="priority-list">
                        @foreach($priorityDistribution as $priority)
                            <div class="priority-item">
                                <div class="priority-info">
                                    <span class="priority-badge priority-{{ strtolower($priority->priorite) }}">
                                        {{ $priority->priorite }}
                                    </span>
                                </div>
                                <div class="priority-count">
                                    {{ $priority->count }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="chart-container small">
                        <canvas id="priorityChart" width="200" height="200"></canvas>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>Aucune donnée de priorité</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Monthly Summary -->
        <div class="stat-card full-width">
            <div class="stat-header">
                <h3 class="stat-title">
                    <i class="fas fa-calendar-alt"></i>
                    Résumé Mensuel
                </h3>
            </div>
            <div class="stat-content">
                @if($monthlyStats && count($monthlyStats) > 0)
                    <div class="monthly-grid">
                        @foreach($monthlyStats as $month)
                            <div class="monthly-item">
                                <h4 class="month-name">{{ $month['month'] }}</h4>
                                <div class="month-stats">
                                    <div class="month-stat">
                                        <span class="stat-number">{{ $month['contacts'] }}</span>
                                        <span class="stat-label">Contacts</span>
                                    </div>
                                    <div class="month-stat">
                                        <span class="stat-number">{{ $month['appointments'] }}</span>
                                        <span class="stat-label">RDV</span>
                                    </div>
                                    <div class="month-stat">
                                        <span class="stat-number">{{ $month['notes'] }}</span>
                                        <span class="stat-label">Notes</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-calendar-alt"></i>
                        <p>Aucune donnée mensuelle</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.statistics-dashboard {
    padding: 1.5rem;
    max-width: 1400px;
    margin: 0 auto;
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding: 2rem;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    border-radius: 1rem;
    color: white;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.page-subtitle {
    margin: 0.5rem 0 0 0;
    opacity: 0.9;
}

.header-actions {
    display: flex;
    gap: 1rem;
}

.controls-section {
    display: flex;
    gap: 2rem;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: white;
    border-radius: 1rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.control-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.control-label {
    font-weight: 600;
    color: #374151;
    font-size: 0.9rem;
}

.control-select {
    padding: 0.75rem 1rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    background: white;
    min-width: 200px;
}

.chart-section {
    margin-bottom: 2rem;
}

.chart-card {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.chart-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    background: #f9fafb;
}

.chart-title {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #374151;
}

.chart-container {
    padding: 2rem;
    display: flex;
    justify-content: center;
    align-items: center;
}

.chart-container.small {
    padding: 1rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 1.5rem;
}

.stat-card {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.stat-card.full-width {
    grid-column: 1 / -1;
}

.stat-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    background: #f9fafb;
}

.stat-title {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #374151;
}

.stat-content {
    padding: 1.5rem;
}

.activity-list, .status-list, .priority-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.activity-item, .status-item, .priority-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    transition: all 0.2s ease;
}

.activity-item:hover, .status-item:hover, .priority-item:hover {
    border-color: #f59e0b;
    box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.1);
}

.activity-name {
    font-weight: 600;
    margin: 0 0 0.25rem 0;
    color: #1f2937;
}

.activity-description {
    margin: 0;
    font-size: 0.875rem;
    color: #6b7280;
}

.activity-stats, .status-count, .priority-count {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: #f59e0b;
}

.stat-label {
    font-size: 0.75rem;
    color: #6b7280;
    text-transform: uppercase;
}

.status-badge, .priority-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-programmé { background: #dbeafe; color: #1e40af; }
.status-confirmé { background: #d1fae5; color: #065f46; }
.status-en-cours { background: #fef3c7; color: #d97706; }
.status-terminé { background: #f3e8ff; color: #7c3aed; }
.status-annulé { background: #fee2e2; color: #dc2626; }
.status-reporté { background: #f1f5f9; color: #475569; }

.priority-basse { background: #f3f4f6; color: #374151; }
.priority-normale { background: #dbeafe; color: #1e40af; }
.priority-haute { background: #fef3c7; color: #d97706; }
.priority-urgente { background: #fee2e2; color: #dc2626; }

.monthly-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.monthly-item {
    padding: 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    text-align: center;
}

.month-name {
    font-weight: 600;
    margin: 0 0 1rem 0;
    color: #1f2937;
}

.month-stats {
    display: flex;
    justify-content: space-around;
    gap: 0.5rem;
}

.month-stat {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.empty-state {
    text-align: center;
    padding: 2rem;
    color: #6b7280;
}

.empty-state i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    color: #d1d5db;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    border: none;
}

.btn-primary {
    background: #f59e0b;
    color: white;
}

.btn-primary:hover {
    background: #d97706;
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.3);
}

@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .header-actions {
        flex-direction: column;
        width: 100%;
    }
    
    .controls-section {
        flex-direction: column;
        gap: 1rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .monthly-grid {
        grid-template-columns: 1fr;
    }
    
    .month-stats {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>

<script>
document.addEventListener('livewire:navigated', () => {
    // Initialize charts when component loads
    initializeCharts();
});

function initializeCharts() {
    // Monthly Trends Chart
    const monthlyCtx = document.getElementById('monthlyTrendsChart');
    if (monthlyCtx) {
        // Chart will be implemented with Chart.js
        const monthlyData = @json($monthlyStats);
        
        // Placeholder for Chart.js implementation
        monthlyCtx.getContext('2d').fillText('Graphique des tendances mensuelles', 50, 100);
    }
    
    // Status Distribution Chart
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        const statusData = @json($statusDistribution);
        
        // Placeholder for Chart.js implementation
        statusCtx.getContext('2d').fillText('Graphique des statuts', 50, 100);
    }
    
    // Priority Distribution Chart
    const priorityCtx = document.getElementById('priorityChart');
    if (priorityCtx) {
        const priorityData = @json($priorityDistribution);
        
        // Placeholder for Chart.js implementation
        priorityCtx.getContext('2d').fillText('Graphique des priorités', 50, 100);
    }
}

// Reinitialize charts when data updates
Livewire.on('statisticsUpdated', () => {
    initializeCharts();
});
</script>
