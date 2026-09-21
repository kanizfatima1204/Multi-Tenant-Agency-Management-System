# GitHub Readiness

The repository includes a GitHub Actions workflow that installs PHP and Node dependencies, migrates and seeds SQLite, runs the Laravel tests, and builds Vite assets. Dependabot is configured to propose weekly Composer, npm, and GitHub Actions updates.

## Publish the project

```bash
git init
git add .
git commit -m "Build Source X multi-tenant agency system"
git branch -M main
git remote add origin https://github.com/YOUR-ACCOUNT/source-x.git
git push -u origin main
```

Before pushing, verify that `.env`, `vendor`, `node_modules`, and generated build assets remain ignored. Enable branch protection on `main` and require the CI workflow to pass before merging pull requests.
