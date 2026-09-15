<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\NasabahProfile;
use App\Models\WasteCategory;
use App\Models\WasteItem;
use App\Models\WasteDeposit;
use App\Models\WasteDepositDetail;
use App\Models\Withdrawal;
use App\Models\Transaction;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Pengguna
        $admin = User::create([
            'name' => 'Administrator Bank Sampah',
            'email' => 'admin@ecobank.id',
            'phone' => '081234567890',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        $petugas = User::create([
            'name' => 'Rian Hidayat (Petugas)',
            'email' => 'petugas@ecobank.id',
            'phone' => '081234567891',
            'role' => 'petugas',
            'password' => Hash::make('password'),
        ]);

        $budi = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'phone' => '081298765432',
            'role' => 'nasabah',
            'password' => Hash::make('password'),
        ]);

        $budiProfile = NasabahProfile::create([
            'user_id' => $budi->id,
            'no_rekening' => 'BS-2026-0101',
            'balance' => 345000,
            'points' => 1250,
            'co2_saved_kg' => 84.50,
            'address' => 'Jl. Kebon Jeruk No. 14, RT 03/RW 05',
            'rt_rw' => '03/05',
            'qr_code_token' => 'QR-BUDI-20260101',
            'status' => 'active',
        ]);

        $siti = User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@gmail.com',
            'phone' => '081345678901',
            'role' => 'nasabah',
            'password' => Hash::make('password'),
        ]);

        $sitiProfile = NasabahProfile::create([
            'user_id' => $siti->id,
            'no_rekening' => 'BS-2026-0102',
            'balance' => 182500,
            'points' => 680,
            'co2_saved_kg' => 42.00,
            'address' => 'Jl. Melati No. 8, RT 01/RW 02',
            'rt_rw' => '01/02',
            'qr_code_token' => 'QR-SITI-20260102',
            'status' => 'active',
        ]);

        $ahmad = User::create([
            'name' => 'Ahmad Dahlan',
            'email' => 'ahmad@gmail.com',
            'phone' => '081512345678',
            'role' => 'nasabah',
            'password' => Hash::make('password'),
        ]);

        $ahmadProfile = NasabahProfile::create([
            'user_id' => $ahmad->id,
            'no_rekening' => 'BS-2026-0103',
            'balance' => 520000,
            'points' => 2100,
            'co2_saved_kg' => 125.00,
            'address' => 'Jl. Kenanga No. 22, RT 04/RW 01',
            'rt_rw' => '04/01',
            'qr_code_token' => 'QR-AHMAD-20260103',
            'status' => 'active',
        ]);

        // 2. Kategori Sampah
        $catPlastik = WasteCategory::create([
            'name' => 'Plastik',
            'slug' => 'plastik',
            'icon' => 'bottle',
            'description' => 'Botol kemasan PET, HDPE tutup galon, kantong kresek bersih, dan gelas plastik bening.',
            'is_active' => true,
        ]);

        $catKertas = WasteCategory::create([
            'name' => 'Kertas & Kardus',
            'slug' => 'kertas-kardus',
            'icon' => 'box',
            'description' => 'Kardus packaging tebal, kertas dokumen/HVS bekas, koran, dan buku pelajaran lama.',
            'is_active' => true,
        ]);

        $catLogam = WasteCategory::create([
            'name' => 'Logam & Kaleng',
            'slug' => 'logam-kaleng',
            'icon' => 'sparkles',
            'description' => 'Kaleng minuman aluminium, besi rongsokan, tembaga kabel, dan seng baja.',
            'is_active' => true,
        ]);

        $catKaca = WasteCategory::create([
            'name' => 'Kaca',
            'slug' => 'kaca',
            'icon' => 'glass',
            'description' => 'Botol kecap/sirup utuh, toples kaca, dan pecahan kaca bersih.',
            'is_active' => true,
        ]);

        $catMinyak = WasteCategory::create([
            'name' => 'Minyak Jelantah',
            'slug' => 'minyak-jelantah',
            'icon' => 'drop',
            'description' => 'Minyak goreng sisa pakai yang disaring bebas remah makanan untuk biofuel.',
            'is_active' => true,
        ]);

        $catElektronik = WasteCategory::create([
            'name' => 'Elektronik (E-Waste)',
            'slug' => 'elektronik',
            'icon' => 'cpu',
            'description' => 'Peralatan elektronik kecil, PCB motherboard, baterai bekas, dan kabel usang.',
            'is_active' => true,
        ]);

        // 3. Item Sampah & Master Harga
        $itemPet = WasteItem::create([
            'waste_category_id' => $catPlastik->id,
            'name' => 'Botol Plastik PET Bening Bersih',
            'unit' => 'kg',
            'buy_price_per_unit' => 4500,
            'points_per_unit' => 15,
            'co2_factor_per_unit' => 2.100,
            'description' => 'Botol air mineral dicuci bersih dan dipipihkan.',
        ]);

        $itemHdpe = WasteItem::create([
            'waste_category_id' => $catPlastik->id,
            'name' => 'Tutup Botol / Plastik HDPE Keras',
            'unit' => 'kg',
            'buy_price_per_unit' => 5000,
            'points_per_unit' => 20,
            'co2_factor_per_unit' => 1.800,
            'description' => 'Plastik jenis HDPE seperti botol sampo, tutup botol air.',
        ]);

        $itemKardus = WasteItem::create([
            'waste_category_id' => $catKertas->id,
            'name' => 'Kardus Box Cokelat Bersih',
            'unit' => 'kg',
            'buy_price_per_unit' => 2200,
            'points_per_unit' => 8,
            'co2_factor_per_unit' => 1.200,
            'description' => 'Kardus coklat kering, dilepas lakbannya.',
        ]);

        $itemHvs = WasteItem::create([
            'waste_category_id' => $catKertas->id,
            'name' => 'Kertas Dokumen / HVS Putih',
            'unit' => 'kg',
            'buy_price_per_unit' => 2500,
            'points_per_unit' => 10,
            'co2_factor_per_unit' => 1.400,
            'description' => 'Kertas putih bekas print atau buku tulis tanpa staples.',
        ]);

        $itemKaleng = WasteItem::create([
            'waste_category_id' => $catLogam->id,
            'name' => 'Kaleng Aluminium Minuman',
            'unit' => 'kg',
            'buy_price_per_unit' => 14000,
            'points_per_unit' => 50,
            'co2_factor_per_unit' => 9.000,
            'description' => 'Kaleng soda/minuman ringan aluminium dalam kondisi bersih.',
        ]);

        $itemBesi = WasteItem::create([
            'waste_category_id' => $catLogam->id,
            'name' => 'Besi Padat / Rongsok',
            'unit' => 'kg',
            'buy_price_per_unit' => 4500,
            'points_per_unit' => 15,
            'co2_factor_per_unit' => 1.800,
            'description' => 'Besi konstruksi, paku, dan peralatan logam sisa.',
        ]);

        $itemKaca = WasteItem::create([
            'waste_category_id' => $catKaca->id,
            'name' => 'Botol Kaca Bening / Sirup Utuh',
            'unit' => 'kg',
            'buy_price_per_unit' => 1200,
            'points_per_unit' => 5,
            'co2_factor_per_unit' => 0.600,
            'description' => 'Botol kaca utuh tanpa sumbat atau label tebal.',
        ]);

        $itemMinyak = WasteItem::create([
            'waste_category_id' => $catMinyak->id,
            'name' => 'Minyak Jelantah Saring',
            'unit' => 'liter',
            'buy_price_per_unit' => 8000,
            'points_per_unit' => 30,
            'co2_factor_per_unit' => 3.200,
            'description' => 'Minyak goreng sisa disaring, dimasukkan ke jerigen/botol tertutup.',
        ]);

        $itemPcb = WasteItem::create([
            'waste_category_id' => $catElektronik->id,
            'name' => 'Papan PCB Motherboard / Komputer',
            'unit' => 'kg',
            'buy_price_per_unit' => 35000,
            'points_per_unit' => 100,
            'co2_factor_per_unit' => 15.000,
            'description' => 'Papan rangkaian elektronik komputer, TV, atau radio.',
        ]);

        // 4. Sample Transaksi Setoran
        $deposit1 = WasteDeposit::create([
            'code' => 'DEP-20260901-001',
            'user_id' => $budi->id,
            'officer_id' => $petugas->id,
            'deposit_type' => 'drop_off',
            'status' => 'completed',
            'total_weight_kg' => 25.50,
            'total_amount' => 145000,
            'total_points' => 520,
            'total_co2_kg' => 45.30,
            'notes' => 'Penyetoran rutin botol dan kardus bersih.',
            'created_at' => now()->subDays(12),
        ]);

        WasteDepositDetail::create([
            'waste_deposit_id' => $deposit1->id,
            'waste_item_id' => $itemPet->id,
            'weight' => 15.00,
            'price_per_unit' => 4500,
            'points_per_unit' => 15,
            'subtotal_amount' => 67500,
            'subtotal_points' => 225,
            'subtotal_co2' => 31.50,
        ]);

        WasteDepositDetail::create([
            'waste_deposit_id' => $deposit1->id,
            'waste_item_id' => $itemKardus->id,
            'weight' => 10.50,
            'price_per_unit' => 2200,
            'points_per_unit' => 8,
            'subtotal_amount' => 23100,
            'subtotal_points' => 84,
            'subtotal_co2' => 12.60,
        ]);

        Transaction::create([
            'user_id' => $budi->id,
            'reference_type' => 'deposit',
            'reference_id' => $deposit1->id,
            'type' => 'credit',
            'amount' => 145000,
            'points' => 520,
            'balance_after' => 145000,
            'points_after' => 520,
            'description' => 'Setoran Sampah #DEP-20260901-001 (25.5 kg)',
            'created_at' => now()->subDays(12),
        ]);

        // 5. Penarikan Dana & Reward
        $withdrawal1 = Withdrawal::create([
            'code' => 'WD-20260905-001',
            'user_id' => $budi->id,
            'amount' => 50000,
            'points_used' => 0,
            'type' => 'ewallet',
            'channel' => 'GoPay',
            'account_number' => '081298765432',
            'account_holder_name' => 'Budi Santoso',
            'status' => 'approved',
            'admin_notes' => 'Transfer berhasil via GoPay Merchant',
            'approved_by' => $admin->id,
            'approved_at' => now()->subDays(8),
            'created_at' => now()->subDays(9),
        ]);

        // Penarikan pending untuk verifikasi fitur approval admin
        Withdrawal::create([
            'code' => 'WD-20260914-002',
            'user_id' => $siti->id,
            'amount' => 100000,
            'points_used' => 0,
            'type' => 'bank_transfer',
            'channel' => 'Bank BCA',
            'account_number' => '8420192841',
            'account_holder_name' => 'Siti Aminah',
            'status' => 'pending',
            'admin_notes' => 'Menunggu verifikasi admin',
            'created_at' => now()->subHours(3),
        ]);
    }
}
