#!/bin/bash

# Load environment variables dari .env
export $(grep -v '^#' .env | xargs)

php artisan key:generate

# Fungsi untuk menunggu database siap
echo "Menunggu database siap..."
until php -r "try { new PDO('mysql:host=${DB_HOST};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}'); echo 'Database siap.'; } catch (PDOException \$e) { exit(1); }"; do
    sleep 3
    echo "Menunggu database..."
done || { echo "Gagal terhubung ke database."; exit 1; }

# Jalankan migrasi
echo "Menjalankan migrasi..."
if php artisan migrate --force; then
    echo "Migrasi berhasil."
else
    echo "Gagal menjalankan migrasi."
    exit 1
fi

# Jalankan seeder
if [ "${RUN_SEEDER}" = "true" ]; then
    echo "Menjalankan seeder..."
    if php artisan db:seed --force; then
        echo "Seeder berhasil."
    else
        echo "Gagal menjalankan seeder."
        exit 1
    fi
else
    echo "Seeder tidak dijalankan (RUN_SEEDER=false)."
fi

# Jalankan server Laravel
echo "Menjalankan server Laravel..."
php artisan serve --host=0.0.0.0 --port=8000