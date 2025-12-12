<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil minimal 3 produk unggulan dari database (kita ambil produk dengan stok > 0)
        $featuredProducts = Product::where('stock', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit(6) // Ambil 6 produk terbaru sebagai produk unggulan
            ->get();

        return view('home', compact('featuredProducts'));
    }
}
