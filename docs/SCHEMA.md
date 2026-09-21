# Database Schema

```text
tenants
  id PK
  name
  slug UNIQUE
  status

users
  id PK
  tenant_id FK -> tenants.id NULL for admin
  name
  email UNIQUE
  password
  role

projects
  id PK
  tenant_id FK -> tenants.id
  name
  description
  status
  due_date

tasks
  id PK
  tenant_id FK -> tenants.id
  project_id FK -> projects.id
  title
  description
  status
  priority
  due_date

project_files
  id PK
  tenant_id FK -> tenants.id
  project_id FK -> projects.id
  name
  path
  mime_type
  size

project_updates
  id PK
  tenant_id FK -> tenants.id
  project_id FK -> projects.id
  user_id FK -> users.id
  body
```
