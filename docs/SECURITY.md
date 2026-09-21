# Security Considerations

## Controls implemented in the prototype

- Laravel hashes passwords through the `hashed` model cast.
- Web authentication regenerates the session on login; logout invalidates the session and rotates the CSRF token.
- Login and API-token endpoints are rate-limited.
- All web mutations use Laravel's CSRF-protected web middleware.
- Policies protect project, task, and file operations by role and tenant.
- Tenant-owned models apply a tenant scope for both session and Sanctum API users.
- Uploads allow only explicitly listed extensions and are limited to 10 MB.
- Files are stored on a non-public disk and downloaded only after authorization.
- Sensitive fields are hidden on the User model; mass assignment is allowlisted.

## Production checklist

1. Set `APP_ENV=production` and `APP_DEBUG=false`.
2. Serve only over HTTPS and configure secure, HTTP-only session cookies.
3. Store `APP_KEY`, database credentials, mail credentials, and storage keys in a secret manager; never commit `.env`.
4. Use PostgreSQL or MySQL with least-privilege application credentials.
5. Configure backups, dependency updates, log retention, monitoring, alerting, and incident response.
6. Put the application behind a reverse proxy/WAF and set environment-appropriate rate limits.
7. Run `composer audit` and `npm audit` regularly, reviewing updates before deployment.
8. Use malware scanning and expiring signed URLs if uploads are exposed externally.
