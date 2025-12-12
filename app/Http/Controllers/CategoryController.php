<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request, $categoryId = null)
    {
        $categories = Category::withCount('products')->get();

        if ($categoryId) {
            $category = Category::find($categoryId);
            if ($category) {
                $products = Product::where('category_id', $category->id)
                    ->where('stock', '>', 0)
                    ->paginate(12);
            } else {
                $products = collect(); // koleksi kosong
            }
        } else {
            $products = Product::where('stock', '>', 0)->paginate(12);
        }

        return view('categories.index', compact('categories', 'products', 'category'));
    }
}
