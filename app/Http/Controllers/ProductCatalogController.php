<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductCatalogController extends Controller
{
    public function index(Request $request, $categoryId = null)
    {
        $query = Product::query();

        // Filter kategori - jika ada URL parameter categoryId, gunakan itu
        if ($categoryId) {
            $category = Category::find($categoryId);
            if ($category) {
                $query->where('category_id', $category->id);
            } else {
                $category = null;
            }
        } else {
            // Jika tidak ada URL parameter categoryId, maka gunakan filter form
            $categoryFilter = $request->get('category_filter');
            if ($categoryFilter) {
                $category = Category::find($categoryFilter);
                if ($category) {
                    $query->where('category_id', $category->id);
                } else {
                    $category = null;
                }
            } else {
                $category = null;
            }
        }

        // Filter nama produk
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter harga minimum
        if ($request->has('min_price') && !empty($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }

        // Filter harga maksimum
        if ($request->has('max_price') && !empty($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter stok
        if ($request->has('min_stock') && !empty($request->min_stock)) {
            $query->where('stock', '>=', $request->min_stock);
        }

        // Tambahkan filter ketersediaan stok jika tidak diset atau min_stock adalah 0
        if (!$request->has('min_stock') || $request->get('min_stock') === null || $request->get('min_stock') === '') {
            $query->where('stock', '>', 0);
        }

        // Sorting
        $sort = $request->get('sort', 'latest'); // Default sort
        switch ($sort) {
            case 'price_low_high':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high_low':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Hitung total produk setelah filter diterapkan
        $totalProducts = $query->count();

        $products = $query->paginate(12);
        $products->appends($request->query());

        $categories = Category::withCount('products')->get();

        return view('categories.index', compact('categories', 'products', 'category', 'totalProducts'));
    }
}
