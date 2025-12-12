<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat entri pertama untuk pengaturan toko
        \App\Models\StoreSetting::create([
            'store_name' => 'Toko Sayur Segar',
            'logo_path' => null,  // nanti akan diupload
            'contact_phone' => '+6281234567890',  // Contoh nomor WA
            'address' => 'Jl. Raya Sayuran No. 123, Kota Segar',
            'description' => 'Toko sayur segar terpercaya sejak 2025, menyediakan berbagai jenis sayuran segar dari petani lokal.',
            'social_media' => json_encode([
                'facebook' => '',
                'instagram' => '',
                'twitter' => ''
            ])
        ]);
    }
}
