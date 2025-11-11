<div class="contact-manager">
    <!-- Header -->
    <div class="manager-header">
        <div class="header-content">
            <h1 class="page-title">
                <i class="fas fa-users"></i>
                Gestion des Contacts
            </h1>
            <p class="page-subtitle">Gérez tous vos contacts en un seul endroit</p>
        </div>
        <div class="header-actions">
            <button wire:click="openCreateModal" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouveau Contact
            </button>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="filters-section">
        <div class="search-box">
            <div class="search-input-group">
                <i class="fas fa-search"></i>
                <input 
                    type="text" 
                    wire:model.live="search" 
                    placeholder="Rechercher par nom, email ou téléphone..."
                    class="search-input"
                >
            </div>
        </div>
        
        <div class="filter-controls">
            <select wire:model.live="statusFilter" class="filter-select">
                <option value="">Tous les statuts</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->id }}">{{ $status->nom }}</option>
                @endforeach
            </select>
            
            <div class="sort-controls">
                <button wire:click="sortBy('nom')" class="sort-btn {{ $sortBy === 'nom' ? 'active' : '' }}">
                    Nom 
                    @if($sortBy === 'nom')
                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                    @endif
                </button>
                <button wire:click="sortBy('created_at')" class="sort-btn {{ $sortBy === 'created_at' ? 'active' : '' }}">
                    Date 
                    @if($sortBy === 'created_at')
                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                    @endif
                </button>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session()->has('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Contacts Grid -->
    <div class="contacts-grid">
        @forelse($contacts as $contact)
            <div class="contact-card" wire:key="contact-{{ $contact->id }}">
                <div class="contact-avatar">
                    <i class="fas fa-user"></i>
                </div>
                
                <div class="contact-info">
                    <h3 class="contact-name">{{ $contact->nom }} {{ $contact->prenom }}</h3>
                    
                    @if($contact->email)
                        <p class="contact-detail">
                            <i class="fas fa-envelope"></i>
                            <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                        </p>
                    @endif
                    
                    @if($contact->telephone)
                        <p class="contact-detail">
                            <i class="fas fa-phone"></i>
                            <a href="tel:{{ $contact->telephone }}">{{ $contact->telephone }}</a>
                        </p>
                    @endif
                    
                    @if($contact->ville)
                        <p class="contact-detail">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $contact->ville }}
                        </p>
                    @endif
                    
                    <div class="contact-status">
                        <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $contact->status->nom)) }}">
                            {{ $contact->status->nom }}
                        </span>
                    </div>
                    
                    <p class="contact-date">
                        <i class="fas fa-calendar"></i>
                        Ajouté le {{ $contact->created_at->format('d/m/Y') }}
                    </p>
                </div>
                
                <div class="contact-actions">
                    <button wire:click="openEditModal({{ $contact->id }})" class="action-btn edit-btn" title="Modifier">
                        <i class="fas fa-edit"></i>
                    </button>
                    <a href="{{ route('contacts.show', $contact) }}" class="action-btn view-btn" title="Voir">
                        <i class="fas fa-eye"></i>
                    </a>
                    <button wire:click="openDeleteModal({{ $contact->id }})" class="action-btn delete-btn" title="Supprimer">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <h3>Aucun contact trouvé</h3>
                <p>Commencez par créer votre premier contact</p>
                <button wire:click="openCreateModal" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Créer un contact
                </button>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($contacts->hasPages())
        <div class="pagination-wrapper">
            {{ $contacts->links() }}
        </div>
    @endif

    <!-- Create/Edit Modal -->
    @if($showCreateModal || $showEditModal)
        <div class="modal-overlay" wire:click="closeModals">
            <div class="modal-content" wire:click.stop>
                <div class="modal-header">
                    <h2 class="modal-title">
                        <i class="fas fa-{{ $showCreateModal ? 'plus' : 'edit' }}"></i>
                        {{ $showCreateModal ? 'Nouveau Contact' : 'Modifier Contact' }}
                    </h2>
                    <button wire:click="closeModals" class="modal-close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form wire:submit.prevent="{{ $showCreateModal ? 'createContact' : 'updateContact' }}" class="modal-form">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nom" class="form-label">Nom *</label>
                            <input type="text" id="nom" wire:model="nom" class="form-input" required>
                            @error('nom') <span class="error-message">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="prenom" class="form-label">Prénom *</label>
                            <input type="text" id="prenom" wire:model="prenom" class="form-input" required>
                            @error('prenom') <span class="error-message">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" wire:model="email" class="form-input">
                            @error('email') <span class="error-message">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="telephone" class="form-label">Téléphone</label>
                            <input type="tel" id="telephone" wire:model="telephone" class="form-input">
                            @error('telephone') <span class="error-message">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="form-group full-width">
                            <label for="adresse" class="form-label">Adresse</label>
                            <input type="text" id="adresse" wire:model="adresse" class="form-input">
                            @error('adresse') <span class="error-message">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="ville" class="form-label">Ville</label>
                            <input type="text" id="ville" wire:model="ville" class="form-input">
                            @error('ville') <span class="error-message">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="code_postal" class="form-label">Code Postal</label>
                            <input type="text" id="code_postal" wire:model="code_postal" class="form-input">
                            @error('code_postal') <span class="error-message">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="pays" class="form-label">Pays</label>
                            <input type="text" id="pays" wire:model="pays" class="form-input">
                            @error('pays') <span class="error-message">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="status_id" class="form-label">Statut *</label>
                            <select id="status_id" wire:model="status_id" class="form-select" required>
                                <option value="">Sélectionner un statut</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}">{{ $status->nom }}</option>
                                @endforeach
                            </select>
                            @error('status_id') <span class="error-message">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="form-group full-width">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea id="notes" wire:model="notes" class="form-textarea" rows="3"></textarea>
                            @error('notes') <span class="error-message">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="modal-actions">
                        <button type="button" wire:click="closeModals" class="btn btn-secondary">
                            Annuler
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            {{ $showCreateModal ? 'Créer' : 'Mettre à jour' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Delete Modal -->
    @if($showDeleteModal && $selectedContact)
        <div class="modal-overlay" wire:click="closeModals">
            <div class="modal-content small" wire:click.stop>
                <div class="modal-header">
                    <h2 class="modal-title">
                        <i class="fas fa-trash text-red-500"></i>
                        Supprimer Contact
                    </h2>
                    <button wire:click="closeModals" class="modal-close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer le contact <strong>{{ $selectedContact->nom }} {{ $selectedContact->prenom }}</strong> ?</p>
                    <p class="text-sm text-gray-600">Cette action est irréversible.</p>
                </div>
                
                <div class="modal-actions">
                    <button wire:click="closeModals" class="btn btn-secondary">
                        Annuler
                    </button>
                    <button wire:click="deleteContact" class="btn btn-danger">
                        <i class="fas fa-trash"></i>
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    @endif
    <style>
.contact-manager {
    padding: 1.5rem;
    max-width: 1400px;
    margin: 0 auto;
}

.manager-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding: 2rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

.filters-section {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}

.search-box {
    flex: 1;
    min-width: 300px;
}

.search-input-group {
    position: relative;
    display: flex;
    align-items: center;
}

.search-input-group i {
    position: absolute;
    left: 1rem;
    color: #6b7280;
    z-index: 1;
}

.search-input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    font-size: 1rem;
}

.filter-controls {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.filter-select {
    padding: 0.75rem 1rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    background: white;
}

.sort-controls {
    display: flex;
    gap: 0.5rem;
}

.sort-btn {
    padding: 0.5rem 1rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    background: white;
    cursor: pointer;
    transition: all 0.2s ease;
}

.sort-btn:hover, .sort-btn.active {
    background: #3b82f6;
    color: white;
    border-color: #3b82f6;
}

.alert {
    padding: 1rem 1.5rem;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.contacts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.contact-card {
    background: white;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    position: relative;
}

.contact-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1);
}

.contact-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.contact-name {
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0 0 1rem 0;
    color: #1f2937;
}

.contact-detail {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0.5rem 0;
    color: #6b7280;
    font-size: 0.9rem;
}

.contact-detail a {
    color: #3b82f6;
    text-decoration: none;
}

.contact-detail a:hover {
    text-decoration: underline;
}

.contact-status {
    margin: 1rem 0;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-prospect { background: #dbeafe; color: #1e40af; }
.status-client-actif { background: #d1fae5; color: #065f46; }
.status-client-inactif { background: #fee2e2; color: #dc2626; }

.contact-date {
    margin: 1rem 0 0 0;
    font-size: 0.8rem;
    color: #9ca3af;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.contact-actions {
    position: absolute;
    top: 1rem;
    right: 1rem;
    display: flex;
    gap: 0.5rem;
}

.action-btn {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.8rem;
}

.edit-btn {
    background: #fef3c7;
    color: #d97706;
}

.edit-btn:hover {
    background: #fbbf24;
    color: white;
}

.view-btn {
    background: #dbeafe;
    color: #2563eb;
    text-decoration: none;
}

.view-btn:hover {
    background: #3b82f6;
    color: white;
}

.delete-btn {
    background: #fee2e2;
    color: #dc2626;
}

.delete-btn:hover {
    background: #ef4444;
    color: white;
}

.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 4rem 2rem;
    color: #6b7280;
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 1rem;
    color: #d1d5db;
}

.empty-state h3 {
    font-size: 1.5rem;
    margin: 1rem 0 0.5rem 0;
    color: #374151;
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 1rem;
}

.modal-content {
    background: white;
    border-radius: 1rem;
    max-width: 800px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-content.small {
    max-width: 400px;
}

.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.25rem;
    cursor: pointer;
    color: #6b7280;
    padding: 0.5rem;
    border-radius: 0.25rem;
}

.modal-close:hover {
    background: #f3f4f6;
}

.modal-form {
    padding: 1.5rem;
}

.modal-body {
    padding: 1.5rem;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-label {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #374151;
}

.form-input, .form-select, .form-textarea {
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    font-size: 1rem;
}

.form-input:focus, .form-select:focus, .form-textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.error-message {
    color: #dc2626;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.modal-actions {
    padding: 1.5rem;
    border-top: 1px solid #e5e7eb;
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
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
    background: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
}

.btn-secondary {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
}

.btn-secondary:hover {
    background: #e5e7eb;
}

.btn-danger {
    background: #dc2626;
    color: white;
}

.btn-danger:hover {
    background: #b91c1c;
}

@media (max-width: 768px) {
    .manager-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .filters-section {
        flex-direction: column;
    }
    
    .filter-controls {
        flex-wrap: wrap;
    }
    
    .contacts-grid {
        grid-template-columns: 1fr;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
}
    </style>
</div>
