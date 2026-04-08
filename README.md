<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Documentation des routes API (Backend)

Cette section documente les endpoints definis dans `routes/api.php`.

### Base URL

- Locale: `http://localhost:8000/api`

### Authentification

- Toutes les routes sont protegees par `auth:sanctum`.
- Un token Sanctum valide doit etre envoye dans l'en-tete:

```http
Authorization: Bearer <token>
```

### Roles utilises

- `ADMIN_SYSTEME`
- `RESPONSABLE_ARCHIVES`
- `AGENT_ACCUEIL`
- `CONSULTANT`
- `ETUDIANT`

### Convention des routes `apiResource`

Chaque ressource expose les routes REST suivantes:

- `GET /{resource}`: lister
- `POST /{resource}`: creer
- `GET /{resource}/{id}`: afficher
- `PUT/PATCH /{resource}/{id}`: modifier
- `DELETE /{resource}/{id}`: supprimer

### Endpoints par ressource

| Ressource | Prefixe | Roles autorises |
| --- | --- | --- |
| Utilisateurs | `/utilisateurs` | `ADMIN_SYSTEME`, `RESPONSABLE_ARCHIVES` |
| Bac infos | `/bacinfos` | `ADMIN_SYSTEME`, `RESPONSABLE_ARCHIVES` |
| Transferts externes | `/transferts` | `ADMIN_SYSTEME`, `RESPONSABLE_ARCHIVES` |
| Etudiants | `/etudiants` | `ADMIN_SYSTEME`, `RESPONSABLE_ARCHIVES`, `AGENT_ACCUEIL` |
| Dossiers d'archive | `/dossiers` | `ADMIN_SYSTEME`, `RESPONSABLE_ARCHIVES`, `AGENT_ACCUEIL`, `CONSULTANT` |
| Documents | `/documents` | `ADMIN_SYSTEME`, `RESPONSABLE_ARCHIVES`, `AGENT_ACCUEIL`, `CONSULTANT` |
| Mouvements | `/mouvements` | `ADMIN_SYSTEME`, `AGENT_ACCUEIL` |
| Reclamations | `/reclamations` | `ADMIN_SYSTEME`, `RESPONSABLE_ARCHIVES`, `AGENT_ACCUEIL`, `CONSULTANT`, `ETUDIANT` |

### Liste detaillee des endpoints

#### `utilisateurs`

- `GET /api/utilisateurs`
- `POST /api/utilisateurs`
- `GET /api/utilisateurs/{id}`
- `PUT/PATCH /api/utilisateurs/{id}`
- `DELETE /api/utilisateurs/{id}`

#### `bacinfos`

- `GET /api/bacinfos`
- `POST /api/bacinfos`
- `GET /api/bacinfos/{id}`
- `PUT/PATCH /api/bacinfos/{id}`
- `DELETE /api/bacinfos/{id}`

#### `transferts`

- `GET /api/transferts`
- `POST /api/transferts`
- `GET /api/transferts/{id}`
- `PUT/PATCH /api/transferts/{id}`
- `DELETE /api/transferts/{id}`

#### `etudiants`

- `GET /api/etudiants`
- `POST /api/etudiants`
- `GET /api/etudiants/{id}`
- `PUT/PATCH /api/etudiants/{id}`
- `DELETE /api/etudiants/{id}`

#### `dossiers`

- `GET /api/dossiers`
- `POST /api/dossiers`
- `GET /api/dossiers/{id}`
- `PUT/PATCH /api/dossiers/{id}`
- `DELETE /api/dossiers/{id}`

#### `documents`

- `GET /api/documents`
- `POST /api/documents`
- `GET /api/documents/{id}`
- `PUT/PATCH /api/documents/{id}`
- `DELETE /api/documents/{id}`

#### `mouvements`

- `GET /api/mouvements`
- `POST /api/mouvements`
- `GET /api/mouvements/{id}`
- `PUT/PATCH /api/mouvements/{id}`
- `DELETE /api/mouvements/{id}`

#### `reclamations`

- `GET /api/reclamations`
- `POST /api/reclamations`
- `GET /api/reclamations/{id}`
- `PUT/PATCH /api/reclamations/{id}`
- `DELETE /api/reclamations/{id}`

### Note

- Les endpoints `/api` ci-dessus supposent que l'application est servie avec `php artisan serve`.
- Les regles fines de validation/champs se trouvent dans les Controllers et Form Requests associes.

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
