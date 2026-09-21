# 1. Complete Architecture

```text
Browser (Vue 3)
      |
      v
Inertia.js + Laravel Web Routes
      |
Session Auth / CSRF / Rate Limit
      |
Tenant Context Middleware
      |
Policies + Global Tenant Scope
      |
Eloquent Models ---------------- Sanctum API
      |                                |
SQLite/MySQL/PostgreSQL          /api/v1/*
```

The tenant boundary is enforced in three layers: `tenant_id` columns + foreign keys, a reusable `BelongsToTenant` global scope, and authorization policies. Admin is the only role intentionally allowed to cross tenant boundaries.

# 2. Database Schema

- `tenants`: agency clients/organizations.
- `users`: users with `tenant_id` and `role` (`admin`, `client`, `team`).
- `projects`: tenant-owned project records.
- `tasks`: tenant-owned tasks linked to projects.
- `project_files`: tenant-owned metadata for private stored files.
- `project_updates`: tenant-owned activity/update posts.
- `sessions`, `cache`, `jobs`, `personal_access_tokens`: Laravel runtime/security infrastructure.

Every tenant-owned table has an indexed `tenant_id` foreign key.

# 3. User Role Structure

| Role | Tenant | Scope |
|---|---|---|
| Admin | null | Platform-wide |
| Team | Required | One tenant |
| Client | Required | One tenant, read-focused |

# 4. Permission System

| Resource | Admin | Team | Client |
|---|---|---|---|
| View projects | All | Own tenant | Own tenant |
| Create projects | Yes, choose tenant | Own tenant | No |
| Update/delete projects | All | Own tenant | No |
| View tasks | All | Own tenant | Own tenant |
| Create/update/delete tasks | Yes | Own tenant | No |
| View files | All | Own tenant | Own tenant |
| Upload/delete files | Yes | Own tenant | No |
| Post updates | Yes | Own tenant | Own tenant |

# 5. Data Isolation Strategy

1. Never accept a client-provided tenant id for ordinary tenant users.
2. `BelongsToTenant` automatically applies `tenant_id = authenticated_user.tenant_id`.
3. Model creation automatically injects the authenticated tenant id for non-admin users.
4. Policies verify tenant ownership before mutations and detail access.
5. Files are stored under `tenants/{tenant_id}/projects/{project_id}` on a private disk.
6. Admin cross-tenant access is explicit rather than accidental.
7. Foreign keys with cascade deletion keep child data attached to the correct tenant lifecycle.

# 6. Authentication Flow

1. User opens `/login`.
2. Laravel validates credentials with throttling.
3. Session ID is regenerated after successful login.
4. `auth` middleware authenticates subsequent requests.
5. `tenant` middleware resolves the user's tenant context.
6. Inertia shares only the authenticated user's safe identity fields.
7. Logout invalidates the session and regenerates the CSRF token.

# 7. Working Prototype

Included screens: login, dashboard, project list, project creation, project detail, tasks, file upload and updates. Seeder creates two tenants so isolation can be demonstrated immediately.

# 8. API Structure

See `docs/API.md`. API tokens use Laravel Sanctum and the same model/policy isolation rules.

# 9. Security Considerations

- CSRF protection for web forms.
- Login throttling.
- Session regeneration after login.
- Authorization policies on object access.
- Tenant global scope.
- Private file storage.
- File type and 10 MB size validation.
- Mass-assignment allowlists.
- Foreign-key constraints.
- No tenant identifiers trusted from client users.
- Sanctum personal access tokens for API clients.
- Production deployment should use HTTPS, secure cookies, secret management, queue workers, centralized logs, backups, monitoring and WAF/rate limits.

# 10. GitHub

The project includes `.gitignore` and GitHub Actions CI. Use the commands in `README.md` to create the repository and push `main`.

# 11. Future Scalability Plan

Phase 1: move from SQLite to PostgreSQL/MySQL and Redis; use object storage (S3-compatible) for files.

Phase 2: add tenant-aware queues, events, notifications, audit logs, search indexes and background file processing.

Phase 3: add database read replicas, cache by tenant, horizontal app workers and CDN-backed private file delivery.

Phase 4: for very large tenants, evaluate schema-per-tenant or database-per-tenant isolation, while retaining a platform control plane.
