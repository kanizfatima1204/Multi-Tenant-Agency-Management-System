# Source X — Multi-Tenant Agency Management System

A production-minded prototype built with **Laravel 12 + Vue 3 + Inertia.js + Sanctum**.

## What is included

- Separate Admin, Client and Team Member dashboard experiences
- Tenant-isolated Projects, Tasks, Files and Updates
- Database-level tenant ownership fields with foreign keys
- Eloquent tenant global scope for non-admin users
- Policy-based authorization for projects, tasks and files
- Session authentication with login throttling
- CSRF protection through Laravel web middleware
- Private file storage with policy-protected download route
- Sanctum personal access token API
- API rate limiting on token authentication
- Seeded demo data for two tenants
- Feature tests for cross-tenant isolation
- Responsive modern UI
- GitHub Actions CI workflow
- Architecture, schema, security and scalability documentation

## Stack

- Laravel 12
- PHP 8.2+
- Vue 3
- Inertia.js 2
- Vite
- Laravel Sanctum
- SQLite by default (easy to switch to MySQL/PostgreSQL)

## Requirements

- PHP 8.2+
- Composer 2+
- Node.js 20+
- npm 10+

## Installation

```bash
cd source-x-multi-tenant-agency
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm install
npm run build
php artisan serve
```

For development with hot reload:

```bash
composer run dev
```

Or use the included setup script:

```bash
bash setup.sh
```

## Demo accounts

| Role | Email | Password | Tenant |
|---|---|---|---|
| Admin | admin@sourcex.test | password | Global |
| Client | client-a@sourcex.test | password | Acme Digital |
| Client | client-b@sourcex.test | password | Beta Studio |
| Team | team@sourcex.test | password | Acme Digital |

## Role model

### Admin

- Sees every tenant and project
- Creates projects for any tenant
- Updates/deletes any project
- Manages cross-tenant operational visibility
- Uses the analytics/control-center dashboard

### Client

- Sees only their tenant's projects, tasks, files and updates
- Can post project updates
- Cannot create/delete projects
- Cannot upload/delete project files
- Uses the client portal dashboard

### Team Member

- Sees only their tenant's projects
- Creates and updates projects inside their tenant
- Creates and updates tasks
- Uploads project files
- Posts updates
- Uses the execution/task dashboard

## Tenant isolation

Every tenant-owned entity has a `tenant_id`.

Non-admin Eloquent queries are automatically restricted by the `BelongsToTenant` global scope. Authorization policies independently verify tenant ownership. This gives defense in depth:

1. Route model binding cannot normally resolve another tenant's record.
2. Eloquent queries are tenant-scoped.
3. Policies reject mismatched tenant ownership.
4. File downloads use the project policy before reading private storage.
5. Admin is the only role allowed to operate across tenants.

## API

Base URL:

```text
/api/v1
```

### Get token

```http
POST /api/v1/auth/token
Content-Type: application/json

{
  "email": "client-a@sourcex.test",
  "password": "password",
  "device_name": "local-dev"
}
```

### Current user

```http
GET /api/v1/me
Authorization: Bearer TOKEN
```

### Projects

```http
GET /api/v1/projects
Authorization: Bearer TOKEN
```

### Project detail

```http
GET /api/v1/projects/{id}
Authorization: Bearer TOKEN
```

## Architecture

Detailed project references:

- [Architecture, roles, permissions, and tenant isolation](docs/ARCHITECTURE.md)
- [Database schema](docs/SCHEMA.md)
- [API reference](docs/API.md)
- [Security controls and production checklist](docs/SECURITY.md)
- [Scalability plan](docs/SCALABILITY.md)
- [GitHub publishing and CI guidance](docs/GITHUB.md)

```text
Browser
  |
  v
Vue 3 + Inertia
  |
  v
Laravel Web Middleware
  |-- auth
  |-- tenant context
  |-- CSRF
  |-- throttle
  |
  v
Controllers
  |
  +--> Policies / Authorization
  |
  +--> Tenant Global Scope
  |
  v
Eloquent Models
  |
  v
MySQL / PostgreSQL / SQLite
```

## Database

```text
tenants
  |
  +-- users
  |
  +-- projects
        |
        +-- tasks
        +-- project_files
        +-- project_updates -- users
```

All tenant-owned tables carry `tenant_id` for explicit isolation, filtering, indexing and future partitioning.

## Security considerations

- Passwords are hashed by Laravel's cast
- Login uses session regeneration after authentication
- Logout invalidates the session and regenerates the CSRF token
- Login endpoint is throttled
- Sanctum token authentication is used for APIs
- File uploads are MIME/extension and size validated
- Files are stored outside the public URL path
- File downloads require authorization
- Mass assignment uses explicit `$fillable`
- Foreign keys use cascading rules where appropriate
- Tenant isolation is enforced at both query and policy layers

## Future scalability

1. Move tenant and project data to MySQL/PostgreSQL.
2. Add Redis for cache, queues and rate limiting.
3. Move files to S3-compatible object storage.
4. Add queue workers for notifications and file processing.
5. Add audit_logs with tenant_id and actor_id.
6. Add role/permission tables if custom per-tenant permissions are needed.
7. Add tenant-level feature flags and subscription plans.
8. Add search indexing for large project/task volumes.
9. Introduce read replicas for analytics-heavy admin views.
10. Add observability with structured logs, metrics and tracing.
11. Add automated backups and tenant restore workflows.
12. Consider database-per-tenant only when contractual or isolation requirements justify the operational complexity.

## Tests

Run:

```bash
php artisan test
```

The included feature tests verify that a client can only see its own tenant's projects and cannot open another tenant's project by ID.

## GitHub

The repository contains a GitHub Actions CI workflow and Dependabot configuration at:

```text
.github/workflows/ci.yml
.github/dependabot.yml
```

Create a repository and push:

```bash
git init
git add .
git commit -m "Build multi-tenant agency management system"
git branch -M main
git remote add origin YOUR_GITHUB_REPOSITORY_URL
git push -u origin main
```
