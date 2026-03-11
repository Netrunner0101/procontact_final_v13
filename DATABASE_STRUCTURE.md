# PostgreSQL Database Structure - Laravel Multi-Business Agenda

## Database Configuration
- **Host:** localhost
- **Port:** 5445
- **Database:** agenda_app
- **Username:** postgres
- **Password:** root

## Tables Overview (20 total)

### Core Business Tables

#### 1. users
- `id` (bigint, primary key)
- `nom` (varchar) - Last name
- `prenom` (varchar) - First name
- `email` (varchar, unique)
- `telephone` (varchar)
- `rue` (varchar) - Street
- `numero_rue` (varchar) - Street number
- `ville` (varchar) - City
- `code_postal` (varchar) - Postal code
- `pays` (varchar) - Country
- `email_verified_at` (timestamp)
- `password` (varchar)
- `remember_token` (varchar)
- `last_login_at` (timestamp)
- `password_reset_token` (varchar)
- `password_reset_expires` (timestamp)
- `google_id` (varchar) - Google OAuth ID
- `apple_id` (varchar) - Apple OAuth ID
- `provider` (varchar) - OAuth provider
- `avatar` (varchar) - Profile picture
- `role` (varchar) - admin/client
- `admin_user_id` (bigint) - For client-admin relationship
- `created_at`, `updated_at` (timestamps)

#### 2. contacts
- `id` (bigint, primary key)
- `user_id` (bigint, foreign key to users)
- `nom` (varchar) - Last name
- `prenom` (varchar) - First name
- `rue` (varchar) - Street
- `numero` (varchar) - Street number
- `ville` (varchar) - City
- `code_postal` (varchar) - Postal code
- `pays` (varchar) - Country
- `state_client` (varchar) - Client state
- `status_id` (bigint, foreign key to statuses)
- `created_at`, `updated_at` (timestamps)

#### 3. statuses
- `id` (bigint, primary key)
- `status_client` (varchar) - Status name
- `created_at`, `updated_at` (timestamps)

#### 4. activites
- `id` (bigint, primary key)
- `nom` (varchar) - Activity name
- `description` (text) - Activity description
- `user_id` (bigint, foreign key to users)
- `created_at`, `updated_at` (timestamps)

#### 5. rendez_vous (appointments)
- `id` (bigint, primary key)
- `user_id` (bigint, foreign key to users)
- `contact_id` (bigint, foreign key to contacts)
- `activite_id` (bigint, foreign key to activites)
- `titre` (varchar) - Appointment title
- `description` (text) - Appointment description
- `date_debut` (date) - Start date
- `date_fin` (date) - End date
- `heure_debut` (time) - Start time
- `heure_fin` (time) - End time
- `created_at`, `updated_at` (timestamps)

#### 6. notes
- `id` (bigint, primary key)
- `user_id` (bigint, foreign key to users) - **FIXED**
- `rendez_vous_id` (bigint, foreign key to rendez_vous)
- `activite_id` (bigint, foreign key to activites)
- `titre` (varchar) - Note title
- `commentaire` (text) - Note content
- `type_note` (enum: 'privee', 'partagee') - Note visibility, default 'privee' - **NEW**
- `auteur` (enum: 'entrepreneur', 'client') - Note author type, default 'entrepreneur' - **NEW**
- `date_create` (timestamp) - Creation date
- `date_update` (timestamp) - Update date
- `created_at`, `updated_at` (timestamps)

#### 7. rappels (reminders)
- `id` (bigint, primary key)
- `user_id` (bigint, foreign key to users) - **ADDED**
- `rendez_vous_id` (bigint, foreign key to rendez_vous)
- `date_rappel` (timestamp) - Reminder date
- `frequence` (varchar) - Frequency (Une fois, Quotidien, etc.)
- `created_at`, `updated_at` (timestamps)

#### 8. emails
- `id` (bigint, primary key)
- `user_id` (bigint, foreign key to users) - **ADDED**
- `contact_id` (bigint, foreign key to contacts)
- `email` (varchar) - Email address
- `created_at`, `updated_at` (timestamps)

#### 9. numero_telephones
- `id` (bigint, primary key)
- `user_id` (bigint, foreign key to users) - **ADDED**
- `contact_id` (bigint, foreign key to contacts)
- `numero_telephone` (varchar) - Phone number
- `created_at`, `updated_at` (timestamps)

#### 10. statistiques
- `id` (bigint, primary key)
- `user_id` (bigint, foreign key to users)
- `activite_id` (bigint, foreign key to activites)
- `mois` (integer) - Month
- `annee` (integer) - Year
- `nombre_contacts` (integer) - Number of contacts
- `nombre_rendez_vous` (integer) - Number of appointments
- `created_at`, `updated_at` (timestamps)

#### 11. client_acces_tokens - **NEW**
- `id` (bigint, primary key)
- `contact_id` (bigint, foreign key to contacts, cascade delete)
- `token` (varchar(255), unique) - UUID magic link token
- `date_creation` (timestamp) - Token creation date
- `is_active` (boolean, default true) - Token active status
- **Indexes:** token, contact_id

### Pivot Tables

#### 12. contact_activite
- `id` (bigint, primary key)
- `contact_id` (bigint, foreign key to contacts)
- `activite_id` (bigint, foreign key to activites)
- `created_at`, `updated_at` (timestamps)

### Laravel System Tables

#### 12. migrations
- Migration tracking table

#### 13. cache & cache_locks
- Application caching tables

#### 14. jobs & job_batches & failed_jobs
- Queue management tables

#### 15. sessions
- Session management table

#### 16. password_reset_tokens
- Password reset functionality

## Seed Data

### Users (3 total)
1. **Admin User**
   - Email: admin@agenda.com
   - Password: password
   - Role: admin

2. **Client User**
   - Email: client@agenda.com
   - Password: password
   - Role: client
   - Admin: linked to admin user

### Statuses (8 total)
- Prospect
- Client actif
- Client inactif
- Lead qualifié
- Lead non qualifié
- En négociation
- Fermé gagné
- Fermé perdu

### Activities (3 total)
- Consultation Médicale
- Coaching Personnel
- Formation Professionnelle

### Contacts (2 total)
- Jean Dupont (Paris)
- Marie Martin (Lyon)

## Recent Fixes Applied

1. **Fixed Migration Order:** Reordered migrations to ensure statuses table is created before contacts table
2. **Added Missing user_id Columns:** Added user_id foreign key to:
   - notes table
   - rappels table
   - emails table
   - numero_telephones table
3. **Fixed Seed Data:** Updated seeders to match actual table structures
4. **Database Connection:** Successfully configured PostgreSQL connection

## Client Portal Feature (NEW)

### Overview
Magic link access for clients — no account, no login required. When an entrepreneur sends an appointment email,
a unique token is generated and included as a portal link. Clients can view their appointment details,
see shared notes, and add their own notes through this portal.

### New Routes (public, no auth)
- `GET /portal?token=xxx` - Portal home, shows latest RDV
- `GET /portal/rdv/{id}?token=xxx` - Specific RDV details
- `GET /portal/notes?token=xxx` - All shared notes
- `POST /portal/note` - Client adds a note

### New Files
- `app/Models/ClientAccesToken.php` - Token model
- `app/Services/TokenService.php` - Token validation/generation
- `app/Services/ClientPortalService.php` - Portal business logic
- `app/Http/Controllers/ClientPortalController.php` - Portal controller
- `resources/views/layouts/portal.blade.php` - Standalone portal layout
- `resources/views/portal/rdv.blade.php` - RDV detail view
- `resources/views/portal/notes.blade.php` - All notes view
- `resources/views/portal/invalid.blade.php` - Error page

### Modified Files
- `app/Models/Note.php` - Added type_note, auteur fields + helpers
- `app/Models/Contact.php` - Added accessToken() relation
- `app/Models/RendezVous.php` - Added notesPartagees() relation
- `app/Mail/RendezVousNotification.php` - Added portalLink parameter
- `app/Http/Controllers/RendezVousController.php` - Token generation on email send
- `app/Http/Controllers/ContactController.php` - Revoke access action
- `app/Livewire/NotesManager.php` - Added type_note field
- `resources/views/emails/rendez-vous-notification.blade.php` - Portal link button
- `resources/views/livewire/notes-manager.blade.php` - Visibility badges + type selector
- `resources/views/contacts/show.blade.php` - Portal access status section
- `routes/web.php` - Portal routes + revoke route

### Security
- Token validated on every portal request
- DB queries scoped to contact_id from token
- Private notes never exposed on portal routes
- Client note content sanitized with strip_tags()
- Invalid/revoked tokens return generic error page

## Application Status
✅ All 20 tables created successfully
✅ All foreign key relationships established
✅ Seed data populated
✅ Application running on http://127.0.0.1:8000
✅ Database structure validated and working

## Login Credentials
- **Admin:** admin@agenda.com / password
- **Client:** client@agenda.com / password
