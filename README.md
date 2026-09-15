# 🌱 Sistem Pengelolaan Bank Sampah

Sistem manajemen Bank Sampah yang dibangun berdasarkan diagram **Rancangan Umum Sistem — Bank Sampah** menggunakan teknologi **Laravel**, **PostgreSQL**, dan **Tailwind CSS**.

Sistem ini memfasilitasi 2 Aktor Utama: **Nasabah** dan **Pengurus/Admin**, dimulai dari alur **Login** $\rightarrow$ **Dashboard**.

---

## 🧭 Arsitektur Sistem Sesuai Rancangan Konstruksi

```
[Rancangan Umum Sistem]
       │
   ┌───┴────────────────┐
   ▼                    ▼
[Nasabah]         [Pengurus/Admin]
   │                    │
   └──────────┬─────────┘
              ▼
           [Login]
              │
              ▼
         [Dashboard]
              │
   ┌──────────┴──────────────────────────────┐
   ▼                                         ▼
FITUR NASABAH (4 Fitur):           FITUR PENGURUS/ADMIN (5 Fitur):
1. Melihat Saldo                   1. Mengelola Data Nasabah (CRUD)
2. Melihat Riwayat Setoran         2. Mengelola Jenis dan Harga Sampah
3. Melihat Riwayat Penarikan       3. Mencatat Setoran Sampah
4. Melihat Harga Sampah            4. Memproses Penarikan Saldo
                                   5. Melihat Laporan Transaksi
```

---

## 🌟 Rincian Fitur Aktor

### A. Fitur Nasabah (4 Fitur)
1. **Melihat Saldo**:
   - Sistem menampilkan jumlah saldo terkini yang dimiliki nasabah berdasarkan transaksi setoran dan penarikan yang telah diproses.
2. **Melihat Riwayat Setoran**:
   - Sistem menampilkan riwayat setoran sampah yang dilakukan nasabah, meliputi: **tanggal setoran**, **jenis sampah**, **berat sampah (kg)**, dan **nilai rupiah hasil konversi**.
3. **Melihat Riwayat Penarikan**:
   - Sistem menampilkan riwayat penarikan saldo nasabah yang meliputi: **tanggal penarikan**, **jumlah saldo yang ditarik**, dan **status penarikan** (*Pending, Selesai, Ditolak*). Nasabah juga dapat mengajukan permohonan penarikan saldo baru.
4. **Melihat Harga Sampah**:
   - Sistem menampilkan daftar jenis sampah beserta harga per kilogram (Rp/kg) yang sedang berlaku.

### B. Fitur Pengurus / Admin (5 Fitur)
1. **Mengelola Data Nasabah**:
   - Pengurus dapat **menambah**, **mengubah (edit)**, **menghapus (delete)**, dan **melihat data nasabah** serta melihat seluruh **riwayat transaksi** setoran dan penarikan nasabah tersebut.
2. **Mengelola Jenis dan Harga Sampah**:
   - Pengurus dapat menambah dan mengubah jenis sampah serta menentukan harga setiap jenis sampah per kilogram berdasarkan harga yang berlaku.
3. **Mencatat Setoran Sampah**:
   - Pengurus mencatat jenis dan berat sampah yang disetorkan nasabah. Sistem menghitung nilai setoran berdasarkan:
     $$\text{Nilai Setoran} = \text{Berat Sampah (kg)} \times \text{Harga per kg}$$
     kemudian secara otomatis menambahkan hasilnya ke saldo nasabah.
4. **Memproses Penarikan Saldo**:
   - Pengurus dapat melihat daftar permintaan penarikan saldo, memproses/menyetujui penarikan, dan otomatis mengurangi saldo nasabah sesuai jumlah yang ditarik.
5. **Melihat Laporan Transaksi**:
   - Pengurus dapat melihat data transaksi setoran dan penarikan untuk membantu pemantauan dan tata kelola bank sampah (dilengkapi filter tanggal dan ekspor CSV).

---

## 🔑 Akun Demo & Quick Role Switcher

Untuk mempermudah pengujian di peramban, bilah atas sistem menyediakan tombol simulasi cepat:
- **Aktor Nasabah**: Budi Santoso (`budi@gmail.com` / password: `password`)
- **Aktor Pengurus/Admin**: Pengurus Bank Sampah (`admin@ecobank.id` / password: `password`)

---

## 🚀 Cara Menjalankan Aplikasi di Mac

1. **Pastikan Homebrew & PHP Terpasang**:
   ```bash
   /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
   brew install php composer
   ```

2. **Buat Database PostgreSQL 18** (yang sudah aktif di port 5432):
   ```bash
   /Library/PostgreSQL/18/bin/createdb -U postgres -h 127.0.0.1 bank_sampah_db
   ```

3. **Jalankan Script Otomatis**:
   ```bash
   cd "/Users/rizkydbuy/Documents/antigravity coding"
   ./setup.sh
   ```

Buka di browser: **`http://localhost:8000`**
# web-pengelolaan-bank-sampah
