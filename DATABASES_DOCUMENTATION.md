# ProContact - Documentation Complète des Bases de Données

## Table des matières

1. [Configuration de la Base de Données](#configuration-de-la-base-de-données)
2. [Schéma Relationnel](#schéma-relationnel)
3. [Tables Applicatives](#tables-applicatives)
4. [Tables Système Laravel](#tables-système-laravel)
5. [Relations entre les Tables](#relations-entre-les-tables)
6. [Seeders & Données Initiales](#seeders--données-initiales)
7. [Factories (Tests)](#factories-tests)
8. [Fichiers de Migration](#fichiers-de-migration)

---

## Configuration de la Base de Données

### Connexion Principale

| Paramètre | Valeur par défaut | Alternative |
|-----------|-------------------|-------------|
| Driver | SQLite | PostgreSQL, MySQL, MariaDB, SQL Server |
| Fichier SQLite | `database/database.sqlite` | - |
| PostgreSQL Host | localhost | - |
| PostgreSQL Port | 5445 | - |
| PostgreSQL DB | agenda_app | - |
| PostgreSQL User | postgres | - |

**Fichier de config :** `config/database.php`

### Services Annexes

| Service | Driver | Configuration |
|---------|--------|---------------|
| Cache | Database | Table `cache` + `cache_locks` |
| Sessions | Database | Table `sessions`, durée 120 min |
| Queue | Database | Tables `jobs`, `job_batches`, `failed_jobs` |
| Redis | phpredis | 127.0.0.1:6379, DB 0 (default) + DB 1 (cache) |

---

## Schéma Relationnel

```
┌──────────┐      ┌──────────┐
│  roles   │──1:N─│  users   │──┐
└──────────┘      └────┬─────┘  │ (admin_user_id → self)
                       │        │
                  1:N  │  1:N   │ 1:N (clients)
                       │        │
           ┌───────────┼────────┘
           │           │
           ▼           ▼
     ┌──────────┐ ┌──────────────┐
     │ activites│ │   contacts   │
     └────┬─────┘ └──┬───┬───┬──┘
          │          │   │   │
          │     1:N  │   │   │ 1:N
          │          │   │   │
          │          ▼   │   ▼
          │  ┌────────┐  │  ┌──────────────────┐
          │  │ emails │  │  │ numero_telephones │
          │  └────────┘  │  └──────────────────┘
          │              │
          │   N:M        │
          ├──────────────┤
          │ (contact_    │
          │  activite)   │
          │              │
          │         1:N  │
          ▼              ▼
     ┌─────────────────────┐
     │    rendez_vous      │
     └───┬────────┬────────┘
         │        │
    1:N  │        │ 1:N
         ▼        ▼
    ┌────────┐ ┌────────┐
    │ notes  │ │rappels │
    └────────┘ └────────┘

     ┌──────────────┐
     │ statistiques │ → activite_id, rendez_vous_id, contact_id
     └──────────────┘

     ┌──────────┐
     │ statuses │──1:N── contacts
     └──────────┘
```

---

## Tables Applicatives

### 1. `roles`

Gère les rôles utilisateur (admin, client).

| Colonne | Type | Contraintes |
|---------|------|-------------|
| id | bigint (PK) | auto-increment |
| nom | string | unique |
| description | string | nullable |
| created_at | timestamp | - |
| updated_at | timestamp | - |

**Constantes :** `ADMIN = 'admin'`, `CLIENT = 'client'`

---

### 2. `users`

Table principale des utilisateurs (admins et clients).

| Colonne | Type | Contraintes |
|---------|------|-------------|
| id | bigint (PK) | auto-increment |
| nom | string | - |
| prenom | string | - |
| email | string | unique |
| telephone | string | nullable |
| rue | string | nullable |
| numero_rue | string | nullable |
| ville | string | nullable |
| code_postal | string | nullable |
| pays | string | nullable |
| password | string | hashed |
| email_verified_at | timestamp | nullable |
| last_login_at | timestamp | nullable |
| password_reset_token | string | nullable |
| password_reset_expires | timestamp | nullable |
| google_id | string | nullable |
| apple_id | string | nullable |
| provider | string | nullable |
| avatar | string | nullable |
| layout_preference | string | default 'default' |
| role_id | bigint (FK) | nullable → roles.id |
| contact_id | bigint (FK) | nullable → contacts.id |
| admin_user_id | bigint (FK) | nullable → users.id (self-ref) |
| remember_token | string | nullable |
| created_at | timestamp | - |
| updated_at | timestamp | - |

**Relations :**
- `belongsTo` : Role, Contact, User (admin)
- `hasMany` : Contact, Activite, RendezVous, Users (clients via admin_user_id)

---

### 3. `contacts`

Informations des contacts/clients commerciaux.

| Colonne | Type | Contraintes |
|---------|------|-------------|
| id | bigint (PK) | auto-increment |
| user_id | bigint (FK) | → users.id |
| nom | string | - |
| prenom | string | nullable |
| rue | string | nullable |
| numero | string | nullable |
| ville | string | nullable |
| code_postal | string | nullable |
| pays | string | nullable |
| state_client | string | nullable |
| status_id | bigint (FK) | nullable → statuses.id |
| created_at | timestamp | - |
| updated_at | timestamp | - |

**Relations :**
- `belongsTo` : User, Status
- `hasMany` : RendezVous, Email, NumeroTelephone
- `belongsToMany` : Activite (pivot `contact_activite`)

---

### 4. `statuses`

Statuts commerciaux des contacts.

| Colonne | Type | Contraintes |
|---------|------|-------------|
| id | bigint (PK) | auto-increment |
| status_client | string | - |
| created_at | timestamp | - |
| updated_at | timestamp | - |

**Relations :**
- `hasMany` : Contact

---

### 5. `activites`

Activités/services proposés par les utilisateurs.

| Colonne | Type | Contraintes |
|---------|------|-------------|
| id | bigint (PK) | auto-increment |
| user_id | bigint (FK) | → users.id |
| nom | string | - |
| description | text | nullable |
| numero_telephone | string | nullable |
| email | string | nullable |
| image | string | nullable |
| created_at | timestamp | - |
| updated_at | timestamp | - |

**Relations :**
- `belongsTo` : User
- `hasMany` : RendezVous, Note, Statistique
- `belongsToMany` : Contact (pivot `contact_activite`)

---

### 6. `rendez_vous`

Rendez-vous/planification.

| Colonne | Type | Contraintes |
|---------|------|-------------|
| id | bigint (PK) | auto-increment |
| user_id | bigint (FK) | → users.id |
| contact_id | bigint (FK) | → contacts.id |
| activite_id | bigint (FK) | → activites.id |
| titre | string | - |
| description | text | nullable |
| date_debut | date | - |
| date_fin | date | nullable |
| heure_debut | time | - |
| heure_fin | time | nullable |
| created_at | timestamp | - |
| updated_at | timestamp | - |

**Relations :**
- `belongsTo` : User, Contact, Activite
- `hasMany` : Note, Rappel, Statistique

---

### 7. `notes`

Notes liées aux rendez-vous et activités.

| Colonne | Type | Contraintes |
|---------|------|-------------|
| id | bigint (PK) | auto-increment |
| user_id | bigint (FK) | nullable → users.id |
| rendez_vous_id | bigint (FK) | nullable → rendez_vous.id |
| activite_id | bigint (FK) | nullable → activites.id |
| titre | string | - |
| commentaire | text | nullable |
| date_create | datetime | - |
| date_update | datetime | nullable |
| created_at | timestamp | - |
| updated_at | timestamp | - |

**Relations :**
- `belongsTo` : RendezVous, Activite

---

### 8. `rappels`

Rappels/alertes sur les rendez-vous.

| Colonne | Type | Contraintes |
|---------|------|-------------|
| id | bigint (PK) | auto-increment |
| user_id | bigint (FK) | nullable → users.id |
| rendez_vous_id | bigint (FK) | → rendez_vous.id |
| date_rappel | datetime | - |
| frequence | string | nullable |
| created_at | timestamp | - |
| updated_at | timestamp | - |

**Relations :**
- `belongsTo` : RendezVous

---

### 9. `emails`

Adresses email des contacts (relation 1:N).

| Colonne | Type | Contraintes |
|---------|------|-------------|
| id | bigint (PK) | auto-increment |
| contact_id | bigint (FK) | → contacts.id |
| email | string | - |
| created_at | timestamp | - |
| updated_at | timestamp | - |

**Relations :**
- `belongsTo` : Contact

---

### 10. `numero_telephones`

Numéros de téléphone des contacts (relation 1:N).

| Colonne | Type | Contraintes |
|---------|------|-------------|
| id | bigint (PK) | auto-increment |
| contact_id | bigint (FK) | → contacts.id |
| numero_telephone | string | - |
| created_at | timestamp | - |
| updated_at | timestamp | - |

**Relations :**
- `belongsTo` : Contact

---

### 11. `statistiques`

Suivi statistique des activités et rendez-vous.

| Colonne | Type | Contraintes |
|---------|------|-------------|
| id | bigint (PK) | auto-increment |
| activite_id | bigint (FK) | nullable → activites.id |
| rendez_vous_id | bigint (FK) | nullable → rendez_vous.id |
| contact_id | bigint (FK) | nullable → contacts.id |
| created_at | timestamp | - |
| updated_at | timestamp | - |

**Relations :**
- `belongsTo` : Activite, RendezVous, Contact

---

### 12. `contact_activite` (Table Pivot)

Association N:M entre contacts et activités.

| Colonne | Type | Contraintes |
|---------|------|-------------|
| id | bigint (PK) | auto-increment |
| contact_id | bigint (FK) | → contacts.id |
| activite_id | bigint (FK) | → activites.id |
| created_at | timestamp | - |
| updated_at | timestamp | - |

---

## Tables Système Laravel

### 13. `cache`

| Colonne | Type |
|---------|------|
| key | string (PK) |
| value | mediumText |
| expiration | integer |

### 14. `cache_locks`

| Colonne | Type |
|---------|------|
| key | string (PK) |
| owner | string |
| expiration | integer |

### 15. `sessions`

| Colonne | Type |
|---------|------|
| id | string (PK) |
| user_id | bigint (FK, nullable) |
| ip_address | string(45, nullable) |
| user_agent | text (nullable) |
| payload | longText |
| last_activity | integer (indexed) |

### 16. `jobs`

| Colonne | Type |
|---------|------|
| id | bigint (PK) |
| queue | string (indexed) |
| payload | longText |
| attempts | tinyint |
| reserved_at | int (nullable) |
| available_at | int |
| created_at | int |

### 17. `job_batches`

| Colonne | Type |
|---------|------|
| id | string (PK) |
| name | string |
| total_jobs | integer |
| pending_jobs | integer |
| failed_jobs | integer |
| failed_job_ids | longText |
| options | mediumText (nullable) |
| cancelled_at | int (nullable) |
| created_at | int |
| finished_at | int (nullable) |

### 18. `failed_jobs`

| Colonne | Type |
|---------|------|
| id | bigint (PK) |
| uuid | string (unique) |
| connection | text |
| queue | text |
| payload | longText |
| exception | longText |
| failed_at | timestamp |

### 19. `password_reset_tokens`

| Colonne | Type |
|---------|------|
| email | string (PK) |
| token | string |
| created_at | timestamp (nullable) |

---

## Relations entre les Tables

### Diagramme des Clés Étrangères

```
users.role_id           → roles.id
users.contact_id        → contacts.id
users.admin_user_id     → users.id (auto-référence)

contacts.user_id        → users.id
contacts.status_id      → statuses.id

activites.user_id       → users.id

rendez_vous.user_id     → users.id
rendez_vous.contact_id  → contacts.id
rendez_vous.activite_id → activites.id

notes.user_id           → users.id
notes.rendez_vous_id    → rendez_vous.id
notes.activite_id       → activites.id

rappels.user_id         → users.id
rappels.rendez_vous_id  → rendez_vous.id

emails.contact_id       → contacts.id

numero_telephones.contact_id → contacts.id

statistiques.activite_id    → activites.id
statistiques.rendez_vous_id → rendez_vous.id
statistiques.contact_id     → contacts.id

contact_activite.contact_id  → contacts.id
contact_activite.activite_id → activites.id
```

### Types de Relations

| Relation | Type | Description |
|----------|------|-------------|
| Role → Users | One-to-Many | Un rôle a plusieurs utilisateurs |
| User → Contacts | One-to-Many | Un utilisateur gère plusieurs contacts |
| User → Activites | One-to-Many | Un utilisateur a plusieurs activités |
| User → RendezVous | One-to-Many | Un utilisateur a plusieurs rendez-vous |
| User → Users (clients) | One-to-Many | Un admin gère plusieurs clients |
| Contact → Emails | One-to-Many | Un contact a plusieurs emails |
| Contact → NumeroTelephones | One-to-Many | Un contact a plusieurs téléphones |
| Contact ↔ Activite | Many-to-Many | Via table pivot `contact_activite` |
| Contact → RendezVous | One-to-Many | Un contact a plusieurs rendez-vous |
| Activite → RendezVous | One-to-Many | Une activité a plusieurs rendez-vous |
| RendezVous → Notes | One-to-Many | Un RDV a plusieurs notes |
| RendezVous → Rappels | One-to-Many | Un RDV a plusieurs rappels |
| Status → Contacts | One-to-Many | Un statut pour plusieurs contacts |

---

## Seeders & Données Initiales

### StatusSeeder

8 statuts commerciaux pré-configurés :

| # | Statut |
|---|--------|
| 1 | Prospect |
| 2 | Client actif |
| 3 | Client inactif |
| 4 | Lead qualifié |
| 5 | Lead non qualifié |
| 6 | En négociation |
| 7 | Fermé gagné |
| 8 | Fermé perdu |

### DatabaseSeeder

Crée un utilisateur de test par défaut.

### Autres Seeders (stubs)

- `UserSeeder.php` - Vide
- `ActiviteSeeder.php` - Vide
- `ContactSeeder.php` - Existe

---

## Factories (Tests)

| Factory | Modèle | Champs générés |
|---------|--------|---------------|
| UserFactory | User | nom, prenom, email, password |
| ContactFactory | Contact | nom, prenom, adresse complète |
| ActiviteFactory | Activite | nom, description |
| StatusFactory | Status | status_client |
| NoteFactory | Note | titre, commentaire, dates |
| RendezVousFactory | RendezVous | titre, description, dates, heures |

---

## Fichiers de Migration

### Ordre chronologique

| # | Fichier | Action |
|---|---------|--------|
| 1 | `0001_01_01_000000_create_users_table.php` | Crée users, password_reset_tokens, sessions |
| 2 | `0001_01_01_000001_create_cache_table.php` | Crée cache, cache_locks |
| 3 | `0001_01_01_000002_create_jobs_table.php` | Crée jobs, job_batches, failed_jobs |
| 4 | `2025_07_18_221347_create_statuses_table.php` | Crée statuses |
| 5 | `2025_07_18_221348_create_activites_table.php` | Crée activites |
| 6 | `2025_07_18_221349_create_contacts_table.php` | Crée contacts |
| 7 | `2025_07_18_221351_create_rendez_vous_table.php` | Crée rendez_vous |
| 8 | `2025_07_18_221352_create_notes_table.php` | Crée notes |
| 9 | `2025_07_18_221353_create_rappels_table.php` | Crée rappels |
| 10 | `2025_07_18_221354_create_emails_table.php` | Crée emails |
| 11 | `2025_07_18_221355_create_numero_telephones_table.php` | Crée numero_telephones |
| 12 | `2025_07_18_221356_create_statistiques_table.php` | Crée statistiques |
| 13 | `2025_07_18_221357_create_contact_activite_table.php` | Crée contact_activite (pivot) |
| 14 | `2025_07_19_001900_add_auth_fields_to_users_table.php` | Ajoute champs auth (login, reset) |
| 15 | `2025_07_19_010858_add_social_auth_to_users_table.php` | Ajoute Google/Apple auth |
| 16 | `2025_07_19_013300_add_role_to_users_table.php` | Ajoute role (string) à users |
| 17 | `2025_07_19_201921_add_user_id_to_notes_table.php` | Ajoute user_id à notes |
| 18 | `2025_07_19_202104_add_user_id_to_rappels_table.php` | Ajoute user_id à rappels |
| 19 | `2026_03_15_000001_create_roles_table.php` | Crée roles table |
| 20 | `2026_03_15_000002_update_users_role_system.php` | Migre vers FK role_id + admin_user_id |

---

## Résumé

| Catégorie | Nombre |
|-----------|--------|
| Tables applicatives | 12 |
| Tables système Laravel | 7 |
| **Total tables** | **19** |
| Modèles Eloquent | 11 |
| Migrations | 23 (dont 3 vides) |
| Seeders | 5 (dont 2 vides) |
| Factories | 6 |

**Stack technique :** Laravel (PHP) avec Eloquent ORM, SQLite par défaut (PostgreSQL en alternative), Redis disponible pour cache.
