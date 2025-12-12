<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil AdminUserSeeder untuk membuat user admin
        $this->call(AdminUserSeeder::class);

        // Panggil CategorySeeder untuk membuat kategori
        $this->call(CategorySeeder::class);

        // Panggil ProductSeeder untuk membuat produk contoh
        $this->call(ProductSeeder::class);

        // Panggil StoreSettingSeeder untuk membuat pengaturan toko
        $this->call(StoreSettingSeeder::class);
    }
}
