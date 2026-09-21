# Future Scalability Plan

## Phase 1: reliable production foundation

- Move from SQLite to PostgreSQL or MySQL.
- Use Redis for cache, queues, sessions, and rate limiting.
- Move private project files to S3-compatible object storage.
- Run queue workers for notifications, exports, and file processing.

## Phase 2: growing tenants and workloads

- Add audit logs with tenant and actor identifiers.
- Add tenant-level plans, feature flags, quotas, and usage metering.
- Cache dashboard aggregates using tenant-aware cache keys.
- Add full-text search and asynchronous indexing for projects and tasks.

## Phase 3: large-scale operations

- Scale application workers horizontally behind a load balancer.
- Add database read replicas for reporting-heavy workloads.
- Use a CDN and signed delivery URLs for file distribution.
- Add structured logs, metrics, traces, uptime checks, and backup/restore drills.

## Tenant isolation evolution

The current shared-database, row-scoped model is appropriate for the prototype and most SaaS workloads. Move to schema-per-tenant or database-per-tenant only for contractual, regulatory, or extreme-scale requirements, because both options add migration and operational complexity.
