<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductDetailController extends Controller
{
    public function show($id)
    {
        // Menggunakan route model binding atau mencari produk berdasarkan id
        $product = Product::with('category')->find($id);

        // Jika produk tidak ditemukan, tampilkan halaman error
        if (!$product) {
            return view('products.not-found', ['productId' => $id]);
        }

        // Ambil produk terkait berdasarkan kategori yang sama
        $relatedProducts = collect();
        if ($product->category_id) {
            $relatedProducts = Product::where('category_id', $product->category_id)
                ->where('id', '!=', $product->id) // Tidak termasuk produk saat ini
                ->where('stock', '>', 0) // Hanya produk yang tersedia
                ->limit(4) // Ambil 4 produk terkait
                ->get();
        }

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
