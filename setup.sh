#!/bin/bash
# ==============================================================================
# Script Instalasi & Menjalankan EcoBank (Bank Sampah Digital)
# Stack: Laravel + PostgreSQL 18 + Tailwind CSS
# ==============================================================================

set -e

echo "========================================================="
echo "🌱 Menyiapkan Sistem EcoBank - Bank Sampah Digital"
echo "========================================================="

export PATH="/opt/homebrew/bin:/usr/local/bin:$PATH"

# 1. Cek PostgreSQL
echo "--> 1. Memeriksa PostgreSQL 18..."
if [ -f "/Library/PostgreSQL/18/bin/psql" ]; then
    echo "    PostgreSQL 18 terdeteksi di /Library/PostgreSQL/18/bin/psql"
    export PATH="/Library/PostgreSQL/18/bin:$PATH"
fi

# 2. Cek PHP & Composer
echo "--> 2. Memeriksa PHP & Composer..."
if ! command -v php &> /dev/null; then
    echo "    [PERHATIAN] PHP belum terpasang di sistem macOS Anda."
    echo "    Silakan jalankan perintah berikut di Terminal Mac Anda:"
    echo "    brew install php composer"
    echo "    (atau unduh installer 1-klik gratis dari https://herd.laravel.com)"
    echo ""
    read -p "Apakah Anda ingin mencoba menginstal Homebrew & PHP sekarang? (y/n): " confirm
    if [ "$confirm" = "y" ] || [ "$confirm" = "Y" ]; then
        if ! command -v brew &> /dev/null; then
            echo "    Mengunduh dan menginstal Homebrew..."
            /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
            eval "$(/opt/homebrew/bin/brew shellenv 2>/dev/null || /usr/local/bin/brew shellenv 2>/dev/null)"
        fi
        brew install php composer
    else
        echo "    Harap instal PHP dan jalankan kembali script ini."
        exit 1
    fi
fi

echo "    PHP Version: $(php -v | head -n 1)"
echo "    Composer Version: $(composer --version | head -n 1)"

# 3. Instal Dependensi Composer
echo "--> 3. Memasang dependensi PHP (Composer)..."
if [ ! -d "vendor" ]; then
    composer install --no-interaction --prefer-dist --optimize-autoloader
else
    echo "    Direktori vendor sudah ada."
fi

# 4. Buat Database PostgreSQL jika belum ada
echo "--> 4. Menyiapkan database PostgreSQL 'bank_sampah_db'..."
PG_BIN="/Library/PostgreSQL/18/bin/psql"
if [ -f "$PG_BIN" ]; then
    echo "    Membuat database bank_sampah_db jika belum ada (masukkan password user postgres jika diminta):"
    "$PG_BIN" -U postgres -h 127.0.0.1 -tc "SELECT 1 FROM pg_database WHERE datname = 'bank_sampah_db'" | grep -q 1 || \
    /Library/PostgreSQL/18/bin/createdb -U postgres -h 127.0.0.1 bank_sampah_db 2>/dev/null || true
fi

# 5. Konfigurasi .env & Key
echo "--> 5. Menyiapkan berkas .env..."
if [ ! -f ".env" ]; then
    cp .env.example .env
fi

if ! grep -q "APP_KEY=base64:" .env; then
    php artisan key:generate
fi

# Pastikan DB_PASSWORD terisi jika kosong
if grep -q "DB_PASSWORD=$" .env; then
    sed -i '' 's/DB_PASSWORD=/DB_PASSWORD=1234/' .env 2>/dev/null || sed -i 's/DB_PASSWORD=/DB_PASSWORD=1234/' .env
fi

# 6. Jalankan Migrasi & Seeder Database
echo "--> 6. Menjalankan migrasi dan seeder database..."
php artisan migrate --seed --force

# 7. Kompilasi Aset Frontend Tailwind
echo "--> 7. Mengompilasi aset frontend Tailwind CSS..."
npm run build

echo ""
echo "========================================================="
echo "🎉 Setup Berhasil!"
echo "========================================================="
echo "Akun Demo yang Siap Digunakan:"
echo "1. Admin    : admin@ecobank.id    (password: password)"
echo "2. Petugas  : petugas@ecobank.id  (password: password)"
echo "3. Nasabah  : budi@gmail.com      (password: password)"
echo ""
echo "Menjalankan server lokal:"
echo "php artisan serve"
echo "Buka peramban: http://localhost:8000"
echo "========================================================="

php artisan serve
