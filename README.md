# Gestion Archives - Backend API (Laravel)

API backend du systeme de gestion des archives universitaires.
Ce service expose des endpoints REST proteges par token Sanctum avec gestion des roles.

## Sommaire

- [Vue d'ensemble](#vue-densemble)
- [Stack technique](#stack-technique)
- [Prerequis](#prerequis)
- [Installation](#installation)
- [Configuration](#configuration)
- [Lancer le projet](#lancer-le-projet)
- [Authentification](#authentification)
- [Roles et autorisations](#roles-et-autorisations)
- [Routes API](#routes-api)
- [Structure du projet](#structure-du-projet)
- [Tests](#tests)
- [Depannage](#depannage)

## Vue d'ensemble

Le backend permet de gerer:

- Utilisateurs
- Etudiants
- Informations bac
- Dossiers d'archive
- Documents
- Mouvements
- Reclamations
- Transferts externes

Les routes principales sont definies dans `routes/api.php`.

## Stack technique

- PHP 8.3+
- Laravel 13
- Laravel Sanctum (authentification par token)
- Base de donnees SQLite par defaut (configurable MySQL/PostgreSQL)
- Vite (assets frontend Laravel)

## Prerequis

- PHP >= 8.3
- Composer
- Node.js >= 20
- npm
- Extension PHP sqlite active (si vous gardez SQLite)

## Installation

```bash
git clone <url-du-repo>
cd Gestion-archives

composer install
npm install

cp .env.example .env
php artisan key:generate
php artisan migrate
```

Option rapide (script composer):

```bash
composer run setup
```

## Configuration

Variables importantes dans `.env`:

- `APP_URL`: URL de l'application (ex: `http://localhost:8000`)
- `DB_CONNECTION`: `sqlite`, `mysql`, etc.
- `QUEUE_CONNECTION`: file de jobs (par defaut `database`)

Exemple SQLite local:

```env
DB_CONNECTION=sqlite
```

Exemple MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_archives
DB_USERNAME=root
DB_PASSWORD=
```

## Lancer le projet

Mode developpement complet (serveur + queue + logs + vite):

```bash
composer run dev
```

Ou en mode simple:

```bash
php artisan serve
```

Base URL API en local:

`http://localhost:8000/api`

## Authentification

Endpoint public:

- `POST /api/login`

Exemple de payload:

```json
{
	"email": "admin@exemple.com",
	"password": "motdepasse"
}
```

Pour les routes protegees, envoyer:

```http
Authorization: Bearer <token>
```

## Roles et autorisations

Roles utilises:

- `ADMIN_SYSTEME`
- `RESPONSABLE_ARCHIVES`
- `AGENT_ACCUEIL`
- `CONSULTANT`
- `ETUDIANT`

Le middleware personnalise de role est enregistre via alias `role`.

## Routes API

Toutes les routes ci-dessous sont sous le prefixe `/api`.

Convention `apiResource` par ressource:

- `GET /{resource}`: lister
- `POST /{resource}`: creer
- `GET /{resource}/{id}`: afficher
- `PUT/PATCH /{resource}/{id}`: modifier
- `DELETE /{resource}/{id}`: supprimer

### Matrice d'acces par ressource

| Ressource | Endpoint de base | Roles autorises |
| --- | --- | --- |
| Utilisateurs | `/utilisateurs` | `ADMIN_SYSTEME`, `RESPONSABLE_ARCHIVES` |
| Bac infos | `/bacinfos` | `ADMIN_SYSTEME`, `RESPONSABLE_ARCHIVES` |
| Transferts externes | `/transferts` | `ADMIN_SYSTEME`, `RESPONSABLE_ARCHIVES` |
| Etudiants | `/etudiants` | `ADMIN_SYSTEME`, `RESPONSABLE_ARCHIVES`, `AGENT_ACCUEIL` |
| Dossiers archives | `/dossiers` | `ADMIN_SYSTEME`, `RESPONSABLE_ARCHIVES`, `AGENT_ACCUEIL`, `CONSULTANT` |
| Documents | `/documents` | `ADMIN_SYSTEME`, `RESPONSABLE_ARCHIVES`, `AGENT_ACCUEIL`, `CONSULTANT` |
| Mouvements | `/mouvements` | `ADMIN_SYSTEME`, `AGENT_ACCUEIL` |
| Reclamations | `/reclamations` | `ADMIN_SYSTEME`, `RESPONSABLE_ARCHIVES`, `AGENT_ACCUEIL`, `CONSULTANT`, `ETUDIANT` |

### Endpoints detailles

#### Utilisateurs

- `GET /api/utilisateurs`
- `POST /api/utilisateurs`
- `GET /api/utilisateurs/{id}`
- `PUT/PATCH /api/utilisateurs/{id}`
- `DELETE /api/utilisateurs/{id}`

#### Bac infos

- `GET /api/bacinfos`
- `POST /api/bacinfos`
- `GET /api/bacinfos/{id}`
- `PUT/PATCH /api/bacinfos/{id}`
- `DELETE /api/bacinfos/{id}`

#### Transferts externes

- `GET /api/transferts`
- `POST /api/transferts`
- `GET /api/transferts/{id}`
- `PUT/PATCH /api/transferts/{id}`
- `DELETE /api/transferts/{id}`

#### Etudiants

- `GET /api/etudiants`
- `POST /api/etudiants`
- `GET /api/etudiants/{id}`
- `PUT/PATCH /api/etudiants/{id}`
- `DELETE /api/etudiants/{id}`

#### Dossiers

- `GET /api/dossiers`
- `POST /api/dossiers`
- `GET /api/dossiers/{id}`
- `PUT/PATCH /api/dossiers/{id}`
- `DELETE /api/dossiers/{id}`

#### Documents

- `GET /api/documents`
- `POST /api/documents`
- `GET /api/documents/{id}`
- `PUT/PATCH /api/documents/{id}`
- `DELETE /api/documents/{id}`

#### Mouvements

- `GET /api/mouvements`
- `POST /api/mouvements`
- `GET /api/mouvements/{id}`
- `PUT/PATCH /api/mouvements/{id}`
- `DELETE /api/mouvements/{id}`

#### Reclamations

- `GET /api/reclamations`
- `POST /api/reclamations`
- `GET /api/reclamations/{id}`
- `PUT/PATCH /api/reclamations/{id}`
- `DELETE /api/reclamations/{id}`

## Tester toutes les API automatiquement

Pour tester toutes les routes de facon automatique, il y a deux approches utiles.

### 1. Collection Postman ou Insomnia + Newman

C'est la methode la plus simple pour valider rapidement toutes les routes HTTP.

Principe:

- creer une collection avec toutes les requetes `login`, `GET`, `POST`, `PUT`, `DELETE`
- stocker le token dans une variable d'environnement apres la connexion
- reexecuter la collection en ligne de commande avec Newman

Exemple d'installation:

```bash
npm install -g newman
```

Exemple d'execution:

```bash
newman run gestion-archives.postman_collection.json -e local.postman_environment.json
```

Conseil pour ce projet:

- faire d'abord un appel `POST /api/login`
- recuperer le token renvoye
- l'utiliser ensuite dans l'en-tete `Authorization: Bearer <token>` pour toutes les autres requetes

### 2. Tests Feature Laravel

Pour une verification plus fiable et automatisable dans le temps, cree des tests PHPUnit / Feature tests.

Ce type de test permet de valider:

- les statuts `200`, `201`, `401`, `403`, `404`
- les droits par role
- les validations de champs
- les retours JSON attendus

Lancement de la suite de tests:

```bash
composer run test
```

Exemple de logique de test:

- creer un utilisateur de test avec le role adequat
- generer un token Sanctum
- appeler chaque endpoint avec `getJson`, `postJson`, `putJson` et `deleteJson`

Si tu veux un vrai "test de toutes les API" en un seul clic, la meilleure solution est:

1. une collection Postman/Newman pour le controle manuel automatise
2. des Feature tests Laravel pour la verification continue dans le projet

## Structure du projet

```text
app/
	Http/
		Controllers/
		Middleware/
	Models/
database/
	migrations/
routes/
	api.php
tests/
```

## Tests

Lancer la suite de tests:

```bash
composer run test
```

## Depannage

- Si une route protegee retourne `401`: verifier le token Sanctum et l'en-tete `Authorization`.
- Si une route retourne `403`: verifier le role de l'utilisateur authentifie.
- Si les migrations echouent: verifier la configuration base de donnees dans `.env`.
- Si Vite ne demarre pas: refaire `npm install` puis `npm run dev`.

## Notes

- Les regles de validation metier sont dans les controllers et/ou form requests.
- Le projet peut etre complete par une documentation OpenAPI (Swagger) pour faciliter les tests d'integration.
