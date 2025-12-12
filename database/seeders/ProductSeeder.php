<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Ambil kategori yang ada di database
        $leafVegetables = Category::where('name', 'Sayuran Daun')->first();
        $fruitVegetables = Category::where('name', 'Sayuran Buah')->first();
        $rootVegetables = Category::where('name', 'Sayuran Akar')->first();
        $spices = Category::where('name', 'Bumbu Dapur')->first();

        // Produk untuk kategori Sayuran Daun
        $leafProducts = [
            [
                'name' => 'Bayam',
                'description' => 'Bayam segar organik, kaya akan zat besi dan vitamin',
                'price' => 15000,
                'stock' => 50,
                'category_id' => $leafVegetables->id ?? null,
            ],
            [
                'name' => 'Sawi Hijau',
                'description' => 'Sawi hijau segar, cocok untuk sayur bening atau tumis',
                'price' => 12000,
                'stock' => 40,
                'category_id' => $leafVegetables->id ?? null,
            ],
            [
                'name' => 'Kangkung',
                'description' => 'Kangkung segar, cocok untuk ditumis',
                'price' => 10000,
                'stock' => 35,
                'category_id' => $leafVegetables->id ?? null,
            ],
            [
                'name' => 'Sawi Putih',
                'description' => 'Sawi putih segar, cocok untuk sup atau ditumis',
                'price' => 13000,
                'stock' => 30,
                'category_id' => $leafVegetables->id ?? null,
            ],
        ];

        // Produk untuk kategori Sayuran Buah
        $fruitProducts = [
            [
                'name' => 'Tomat',
                'description' => 'Tomat segar, cocok untuk lalapan atau bumbu masakan',
                'price' => 18000,
                'stock' => 45,
                'category_id' => $fruitVegetables->id ?? null,
            ],
            [
                'name' => 'Cabai Merah',
                'description' => 'Cabai merah segar, pedas dan berkualitas tinggi',
                'price' => 20000,
                'stock' => 25,
                'category_id' => $fruitVegetables->id ?? null,
            ],
            [
                'name' => 'Terong',
                'description' => 'Terong bulat segar, cocok untuk lalapan',
                'price' => 16000,
                'stock' => 30,
                'category_id' => $fruitVegetables->id ?? null,
            ],
            [
                'name' => 'Timun',
                'description' => 'Timun segar, cocok untuk lalapan',
                'price' => 14000,
                'stock' => 40,
                'category_id' => $fruitVegetables->id ?? null,
            ],
        ];

        // Produk untuk kategori Sayuran Akar
        $rootProducts = [
            [
                'name' => 'Wortel',
                'description' => 'Wortel segar, kaya akan vitamin A',
                'price' => 25000,
                'stock' => 35,
                'category_id' => $rootVegetables->id ?? null,
            ],
            [
                'name' => 'Lobak',
                'description' => 'Lobak putih segar, cocok untuk sup',
                'price' => 12000,
                'stock' => 25,
                'category_id' => $rootVegetables->id ?? null,
            ],
            [
                'name' => 'Umbi Bit',
                'description' => 'Umbi bit segar, warna merah alami',
                'price' => 22000,
                'stock' => 20,
                'category_id' => $rootVegetables->id ?? null,
            ],
            [
                'name' => 'Kentang',
                'description' => 'Kentang segar, cocok untuk berbagai masakan',
                'price' => 18000,
                'stock' => 50,
                'category_id' => $rootVegetables->id ?? null,
            ],
        ];

        // Produk untuk kategori Bumbu Dapur
        $spiceProducts = [
            [
                'name' => 'Bawang Merah',
                'description' => 'Bawang merah segar untuk bumbu masakan',
                'price' => 20000,
                'stock' => 60,
                'category_id' => $spices->id ?? null,
            ],
            [
                'name' => 'Bawang Putih',
                'description' => 'Bawang putih segar untuk bumbu masakan',
                'price' => 25000,
                'stock' => 55,
                'category_id' => $spices->id ?? null,
            ],
            [
                'name' => 'Jahe',
                'description' => 'Jahe segar, untuk jamu atau bumbu masakan',
                'price' => 15000,
                'stock' => 30,
                'category_id' => $spices->id ?? null,
            ],
            [
                'name' => 'Kunyit',
                'description' => 'Kunyit segar, untuk jamu atau bumbu kuning',
                'price' => 18000,
                'stock' => 25,
                'category_id' => $spices->id ?? null,
            ],
        ];

        // Gabungkan semua produk
        $allProducts = array_merge($leafProducts, $fruitProducts, $rootProducts, $spiceProducts);

        // Insert produk-produk ke database
        foreach ($allProducts as $product) {
            Product::create($product);
        }
    }
}