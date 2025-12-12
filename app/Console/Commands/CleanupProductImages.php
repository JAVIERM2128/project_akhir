<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupProductImages extends Command
{
    protected $signature = 'products:cleanup-images';
    protected $description = 'Menghapus referensi gambar produk yang tidak valid dari database';

    public function handle()
    {
        $products = Product::whereNotNull('image')->get();
        $count = 0;
        
        foreach ($products as $product) {
            $imagePath = storage_path('app/public/products/' . $product->image);
            
            if (!file_exists($imagePath)) {
                $this->info("Menghapus referensi gambar tidak valid: {$product->image} (Produk: {$product->name})");
                $product->image = null;
                $product->save();
                $count++;
            }
        }
        
        $this->info("Selesai! {$count} produk diperbaiki.");
    }
}