<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::with(['user', 'transactionItems.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.transactions.index', compact('transactions'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'transactionItems.product', 'statusHistories.user']);

        return view('admin.transactions.show', compact('transaction'));
    }

    /**
     * Update the status of the specified resource.
     */
    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,cancelled,shipped,delivered',
            'note' => 'nullable|string',
        ]);

        $oldStatus = $transaction->status;
        $newStatus = $request->status;

        // Update status transaksi
        $transaction->update([
            'status' => $newStatus,
            'paid_at' => $newStatus === 'paid' ? now() : $transaction->paid_at,
        ]);

        // Catat perubahan status di history
        TransactionStatusHistory::create([
            'transaction_id' => $transaction->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'note' => $request->note,
            'changed_by' => Auth::id(),
        ]);

        return redirect()->route('admin.transactions.index')->with('success', 'Status transaksi berhasil diperbarui.');
    }

    /**
     * Upload receipt for the specified transaction.
     */
    public function uploadReceipt(Request $request, Transaction $transaction)
    {
        $request->validate([
            'receipt' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048', // maks 2MB
        ]);

        // Hapus file lama jika ada
        if ($transaction->receipt_path) {
            \Storage::delete('public/receipts/' . $transaction->receipt_path);
        }

        // Simpan file resi
        $receiptFile = $request->file('receipt');
        $fileName = 'receipt_' . $transaction->id . '_' . time() . '.' . $receiptFile->getClientOriginalExtension();
        $receiptFile->storeAs('public/receipts', $fileName);

        $transaction->update([
            'receipt_path' => $fileName,
        ]);

        return redirect()->back()->with('success', 'Resi berhasil diupload.');
    }
}
