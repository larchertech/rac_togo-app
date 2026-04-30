# RAC-TOGO

## Application de Gestion des Alumni & Processus Electoral
### Compassion International Togo

---

## STACK TECHNIQUE

| Couche | Technologie |
|--------|-------------|
| Backend | Laravel 11 + PHP 8.3 |
| Frontend | React 18 + Vite + Tailwind CSS |
| Base de donnees | PostgreSQL 16 |
| Cache & Queues | Redis 7 |
| Auth | Sanctum + OTP (WhatsApp/Email/SMS) |
| Infra | Docker Compose |

---

## DEMARRAGE RAPIDE

### 1. Cloner et demarrer les services

```bash
cd rac-togo
docker-compose up -d
```

### 2. Installer le backend

```bash
cd backend
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve --host=0.0.0.0
```

### 3. Installer le frontend

```bash
cd frontend
npm install
npm run dev
```

---

## STRUCTURE DU PROJET

```
rac-togo/
├── docker-compose.yml          # Infra complete (Postgres, Redis, Backend, Frontend)
├── backend/
│   ├── app/
│   │   ├── Models/             # 12 modeles Eloquent avec relations
│   │   ├── Http/
│   │   │   ├── Controllers/    # 10 controllers (Auth, Alumni, Election, Vote...)
│   │   │   ├── Requests/       # 5 Form Requests (validation stricte)
│   │   │   └── Resources/      # 5 API Resources (format standardise)
│   │   ├── Services/           # 7 services metier (Vote, OTP, WhatsApp, SMS...)
│   │   ├── Policies/           # 3 policies (Vote, Candidature, Election)
│   │   ├── Middleware/         # 2 middleware (Roles, Audit)
│   │   └── Providers/
│   ├── database/
│   │   ├── migrations/         # 12 migrations (ordre exact respecte)
│   │   ├── factories/          # 7 factories pour les tests
│   │   └── seeders/            # 6 seeders (clusters, CDEJ, alumni, elections...)
│   ├── routes/
│   │   ├── api.php             # Routes API v1 avec middleware
│   │   ├── web.php
│   │   └── console.php
│   ├── tests/
│   │   └── Feature/
│   │       └── VoteTest.php    # Tests securite du vote
│   ├── composer.json
│   ├── Dockerfile
│   └── .env.example
└── frontend/
    ├── src/
    │   ├── pages/              # 16 pages React
    │   │   ├── Auth/Login.jsx
    │   │   ├── Onboarding/Inscription.jsx
    │   │   ├── Dashboard/Dashboard.jsx
    │   │   ├── Alumni/Profil.jsx + Annuaire.jsx
    │   │   ├── Elections/
    │   │   │   ├── ElectionsList.jsx
    │   │   │   ├── ElectionDetail.jsx
    │   │   │   ├── DepotCandidature.jsx
    │   │   │   └── BureauDeVote.jsx    # CRITIQUE : vote securise
    │   │   ├── Cotisations/Cotisations.jsx
    │   │   ├── Organigramme/Organigramme.jsx
    │   │   └── Admin/
    │   │       ├── CommissionDashboard.jsx
    │   │       ├── ValidationCandidatures.jsx
    │   │       └── Proclamation.jsx
    │   ├── components/
    │   │   ├── ui/             # 13 composants UI reutilisables
    │   │   │   ├── Button.jsx, Card.jsx, Modal.jsx, Badge.jsx
    │   │   │   ├── Input.jsx, Select.jsx, FileUpload.jsx
    │   │   │   ├── DataTable.jsx, StatCard.jsx, ProgressBar.jsx
    │   │   │   ├── Timeline.jsx, Alert.jsx, QRCard.jsx
    │   │   ├── charts/         # 3 composants Recharts
    │   │   │   ├── ClusterBarChart.jsx
    │   │   │   ├── AlumniDonut.jsx
    │   │   │   └── ActivityArea.jsx
    │   │   └── layout/         # Sidebar, Topbar, AppLayout
    │   ├── hooks/              # 7 custom hooks (React Query)
    │   ├── api/                # 6 modules API (axios + endpoints)
    │   ├── store/              # 2 stores Zustand (auth + app)
    │   ├── App.jsx             # Router avec PrivateRoute
    │   ├── main.jsx            # Entry point
    │   └── index.css           # Tailwind + pattern kente
    ├── package.json
    ├── vite.config.js
    ├── tailwind.config.js
    ├── Dockerfile
    └── index.html
```

---

## COMPTES DE TEST

| Telephone | Role | Nom |
|-----------|------|-----|
| +22890000001 | admin | Alex Guenoukpatí |
| +22890000002 | cena | President CENA |
| +22890000003 | ben | President BEN |
| +22890000004 | alumni | Alumni test Lome Est |
| +22890000005 | bla | President BLA CDEJ test |

---

## SECURITE DU VOTE

Le module vote implemente les regles absolues :

1. **Separation physique** : `emargement` et `resultats` ne partagent jamais de cle etrangere directe
2. **Secret du vote** : aucune colonne `candidat_id` dans `emargement`, aucune `electeur_id` dans `resultats`
3. **Double vote bloque** : contrainte unique `election_id + electeur_id`
4. **Transaction atomique** : `DB::transaction()` garantit l'integrite
5. **Audit complet** : toute action critique est loggee dans `audit_logs`
6. **Permissions Spatie** : chaque route protegee par `authorize()` ou `role:`

---

## CALENDRIER ELECTORAL

| Date | Evenement |
|------|-----------|
| 9 mai 2026 | Vote BLA (CDEJ) |
| 30 mai 2026 | Vote BCA (Cluster) |
| 20 juin 2026 | Vote BE (National) |

---

## COMMANDES UTILES

```bash
# Backend
php artisan migrate:fresh --seed
php artisan test
php artisan horizon
php artisan queue:work

# Frontend
npm run dev
npm run build
npm run preview

# Docker
docker-compose up -d
docker-compose logs -f backend
docker-compose exec backend php artisan migrate
```

---

## ENVIRONNEMENT REQUIS

- Docker 24+ & Docker Compose
- Node.js 20+
- PHP 8.3+ (si local sans Docker)
- PostgreSQL 16+
- Redis 7+

---

**Compassion International Togo — RAC-TOGO 2026**
