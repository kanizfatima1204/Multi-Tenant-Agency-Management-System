#!/usr/bin/env bash
set -euo pipefail

composer install
[ -f .env ] || cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm install
npm run build

echo
 echo "Setup complete. Start the application with:"
 echo "php artisan serve"
