#!/bin/bash

# Load environment variables dari .env
export $(grep -v '^#' .env | sed -E 's/(^|[^\\])#.*$//' | xargs -d '\n')

# Generate APP_KEY
php artisan key:generate

# Fungsi untuk menunggu database siap
echo "Menunggu database siap..."
until php -r "try { new PDO('mysql:host=${DB_HOST};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}'); echo 'Database siap.'; } catch (PDOException \$e) { exit(1); }"; do
    sleep 3
    echo "Menunggu database..."
done || { echo "Gagal terhubung ke database."; exit 1; }

# Cek apakah migrasi diaktifkan di .env
if [ "${MIGRATE_ON_START}" = "true" ]; then
    echo "Menjalankan migrasi..."
    # php artisan migrate:fresh --seed    
    # Coba menjalankan migrasi, jika gagal lakukan wipe dan coba lagi
    if [ "${RUN_SEEDER}" = "true" ]; then
        if php artisan migrate:fresh --seed --force; then
            echo "Migrasi berhasil."
        else
            echo "Gagal menjalankan migrasi."
            exit 1
        fi
    else
        if php artisan migrate --force; then
            echo "Migrasi berhasil."
            echo "Seeder tidak dijalankan. Karena pengaturan RUN_SEEDER tidak diatur ke true."
        else
            echo "Gagal menjalankan migrasi."
            exit 1
        fi
    fi
else
    echo "Migrasi dilewati karena pengaturan MIGRATE_ON_START tidak diatur ke true."
fi

# Jalankan server Laravel
echo "Menjalankan server Laravel..."
php artisan serve --host=0.0.0.0 --port=8000
