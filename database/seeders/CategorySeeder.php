<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Sayuran Daun',
                'description' => 'Berbagai jenis sayuran daun segar seperti bayam, sawi, kangkung, dan sebagainya'
            ],
            [
                'name' => 'Sayuran Buah',
                'description' => 'Sayuran yang berasal dari buah seperti tomat, cabai, terong, dan sebagainya'
            ],
            [
                'name' => 'Sayuran Akar',
                'description' => 'Sayuran yang tumbuh dari akar seperti wortel, lobak, dan sebagainya'
            ],
            [
                'name' => 'Bumbu Dapur',
                'description' => 'Berbagai jenis bumbu dapur segar seperti bawang, jahe, kunyit, dan sebagainya'
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
