<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page with cart items and balance check.
     */
    public function index()
    {
        $user = Auth::user();
        $cartItems = Cart::with(['product'])->where('user_id', $user->id)->get();

        $totalAmount = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $balance = $user->balance ?? 0;

        // Check if user has sufficient balance
        $hasSufficientBalance = $balance >= $totalAmount;

        return view('checkout.index', compact('cartItems', 'totalAmount', 'balance', 'hasSufficientBalance'));
    }

    /**
     * Process the checkout and create transaction.
     */
    public function process(Request $request)
    {
        $user = Auth::user();
        $cartItems = Cart::with(['product'])->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('checkout.index')->with('error', 'Keranjang belanja kosong.');
        }

        $totalAmount = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $balance = $user->balance ?? 0;

        if ($balance < $totalAmount) {
            return redirect()->route('checkout.index')->with('error', 'Saldo tidak mencukupi untuk melakukan pembayaran.');
        }

        // Create transaction
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'payment_method' => 'wallet', // Indicate payment from wallet
        ]);

        // Create transaction items
        foreach ($cartItems as $cartItem) {
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->price,
            ]);

            // Update product stock
            $product = $cartItem->product;
            $product->update([
                'stock' => $product->stock - $cartItem->quantity
            ]);
        }

        // Deduct amount from user balance
        $user->update([
            'balance' => $balance - $totalAmount
        ]);

        // Clear the cart
        Cart::where('user_id', $user->id)->delete();

        return redirect()->route('dashboard')->with('success', 'Transaksi berhasil dibuat. Mohon menunggu konfirmasi pembayaran.');
    }
}
