#!/usr/bin/env bash
#
# Deploy / update Keluhin backend di VPS.
# Pakai: cd /var/www/keluhin-backend && ./deploy.sh
#
# set -e  -> berhenti otomatis kalau ada step gagal (gak lanjut ke step bahaya)
set -e

cd "$(dirname "$0")"

echo "==> Maintenance mode ON"
php artisan down || true

echo "==> Pull kode terbaru"
git pull origin main

echo "==> Composer install"
composer install --no-dev --optimize-autoloader

echo "==> Migrate database"
php artisan migrate --force

# Symlink storage (idempotent — aman diulang)
if [ ! -L public/storage ]; then
  echo "==> Bikin storage symlink"
  php artisan storage:link
fi

echo "==> Rebuild cache"
php artisan config:clear && php artisan config:cache
php artisan route:clear  && php artisan route:cache
php artisan view:clear

# Restart queue worker — aktifkan kalau nanti pakai job/queue
# php artisan queue:restart

echo "==> Fix permission storage"
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

echo "==> Maintenance mode OFF"
php artisan up

echo "✅ Deploy selesai"
