#!/bin/sh
set -e

echo "Esperando a MySQL..."
until php artisan migrate --force; do
  echo "MySQL no está listo, reintentando en 3s..."
  sleep 3
done

echo "✅ Migraciones listas"

if [ "${APP_ENV}" != "testing" ] && [ "${SEED_ON_BOOT:-true}" = "true" ]; then
  php artisan db:seed --force
  echo "✅ Seeders cargados"
else
  echo "⏭️  Seeding omitido (APP_ENV=${APP_ENV}, SEED_ON_BOOT=${SEED_ON_BOOT:-true})"
fi

echo "🚀 Iniciando Laravel en :8000"
php artisan serve --host=0.0.0.0 --port=8000
