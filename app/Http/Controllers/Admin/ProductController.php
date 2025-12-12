<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // maks 10MB
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $data = $request->except(['image']);

        // Proses atribut unit
        if ($request->has('attributes')) {
            $data['attributes'] = $request->input('attributes');
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            // Gunakan Storage facade untuk menyimpan ke disk public secara eksplisit
            Storage::disk('public')->put('products/' . $imageName, file_get_contents($image));
            $data['image'] = $imageName;
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // maks 10MB
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $data = $request->except(['image']);

        // Proses atribut unit
        if ($request->has('attributes')) {
            $data['attributes'] = $request->input('attributes');
        } else {
            $data['attributes'] = null; // Kosongkan atribut jika tidak disediakan
        }

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image) {
                Storage::disk('public')->delete('products/' . $product->image);
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            // Gunakan Storage facade untuk menyimpan ke disk public secara eksplisit
            Storage::disk('public')->put('products/' . $imageName, file_get_contents($image));
            $data['image'] = $imageName;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Validasi jika produk pernah dibeli (muncul di transaction_items)
        $hasBeenPurchased = $product->transactionItems()->exists();

        if ($hasBeenPurchased) {
            return redirect()->route('admin.products.index')->with('error', 'Produk tidak dapat dihapus karena pernah dibeli oleh pelanggan.');
        }

        // Hapus gambar jika ada
        if ($product->image) {
            Storage::disk('public')->delete('products/' . $product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
