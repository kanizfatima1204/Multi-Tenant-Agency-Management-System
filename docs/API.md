# API Reference

Base URL: `/api/v1`

All endpoints return JSON. Send `Accept: application/json`. Authenticated endpoints require `Authorization: Bearer TOKEN`.

## Issue a token

`POST /auth/token`

```json
{"email":"client-a@sourcex.test","password":"password","device_name":"demo"}
```

The response contains a Sanctum bearer token and safe user identity fields. Invalid credentials return `422`; this endpoint is rate-limited.

## Current user

`GET /me`

Returns the authenticated user and their tenant identity.

## List projects

`GET /projects`

Returns a paginated project collection with task and update counts. Admins receive all projects; Team and Client tokens receive only projects for their tenant.

## Get one project

`GET /projects/{project}`

Returns the project, tasks, files, and updates. Access across tenant boundaries is rejected with `403` or not resolved by the tenant query scope.

## API security model

Sanctum authenticates the token, the tenant scope filters tenant-owned Eloquent queries using the authenticated API user, and policies authorize individual-resource access. API consumers must never rely on a client-supplied `tenant_id`.
