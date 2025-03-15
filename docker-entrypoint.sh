#!/bin/bash

# Load environment variables dari .env
export $(grep -v '^#' .env | xargs)

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

    # Coba menjalankan migrasi, jika gagal lakukan wipe dan coba lagi
    if php artisan migrate --force; then
        echo "Migrasi berhasil."
    else
        echo "Gagal menjalankan migrasi. Melakukan wipe..."

        # Jika WIPE_DATABASE diaktifkan, lakukan wipe database
        if [ "${WIPE_DATABASE}" = "true" ]; then
            echo "Menghapus database..."
            # Lakukan rollback pada migrasi yang ada
            php artisan db:wipe --force

            # Setelah wipe, coba jalankan migrasi lagi
            if php artisan migrate --force; then
                echo "Migrasi berhasil setelah wipe."
            else
                echo "Gagal menjalankan migrasi setelah wipe."
                exit 1
            fi
        elsep
            echo "Migrasi gagal, tapi wipe database tidak diaktifkan (WIPE_DATABASE=false)."
            exit 1
        fi
    fi
else
    echo "Migrasi dilewati karena pengaturan MIGRATE_ON_START tidak diatur ke true."
fi

# Jalankan seeder jika diatur di .env
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
