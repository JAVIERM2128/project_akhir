<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display the user's cart.
     */
    public function index()
    {
        $cartItems = Cart::with(['product'])->where('user_id', Auth::id())->get();
        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Add item to cart.
     */
    public function add(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($productId);

        // Check if product is available
        if ($product->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Jumlah produk melebihi stok yang tersedia.');
        }

        // Check if item already exists in cart
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            // Update quantity if item already exists
            $newQuantity = $cartItem->quantity + $request->quantity;

            // Check if new quantity exceeds stock
            if ($product->stock < $newQuantity) {
                return redirect()->back()->with('error', 'Jumlah produk melebihi stok yang tersedia.');
            }

            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            // Create new cart item
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    /**
     * Update quantity in cart.
     */
    public function update(Request $request, $cartId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = Cart::where('user_id', Auth::id())->findOrFail($cartId);
        $product = $cartItem->product;

        // Check if new quantity exceeds stock
        if ($product->stock < $request->quantity) {
            return response()->json(['error' => 'Jumlah produk melebihi stok yang tersedia.'], 422);
        }

        $cartItem->update(['quantity' => $request->quantity]);

        $subtotal = $cartItem->quantity * $cartItem->product->price;
        $total = Cart::with(['product'])->where('user_id', Auth::id())->get()->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return response()->json([
            'success' => true,
            'subtotal' => $subtotal,
            'total' => $total,
            'formatted_subtotal' => 'Rp ' . number_format($subtotal, 2, ',', '.'),
            'formatted_total' => 'Rp ' . number_format($total, 2, ',', '.')
        ]);
    }

    /**
     * Remove item from cart.
     */
    public function remove($cartId)
    {
        $cartItem = Cart::where('user_id', Auth::id())->findOrFail($cartId);
        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}
