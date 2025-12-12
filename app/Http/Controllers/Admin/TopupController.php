<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Topup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Menampilkan daftar topup dengan status pending
        $topups = Topup::with(['user'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.topups.index', compact('topups'));
    }

    /**
     * Display all topups (including approved/rejected)
     */
    public function all()
    {
        $topups = Topup::with(['user', 'approvedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.topups.all', compact('topups'));
    }

    /**
     * Approve a topup request.
     */
    public function approve(Topup $topup)
    {
        // Perbarui status menjadi success dan catat informasi approval
        $topup->update([
            'status' => 'success',
            'completed_at' => now(),
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        // Tambahkan jumlah topup ke saldo user
        $user = $topup->user;
        $user->update([
            'balance' => ($user->balance ?? 0) + $topup->amount
        ]);

        return redirect()->route('admin.topups.index')->with('success', 'Top up berhasil disetujui dan saldo user telah diperbarui.');
    }

    /**
     * Reject a topup request.
     */
    public function reject(Request $request, Topup $topup)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:255',
        ]);

        // Perbarui status menjadi rejected dan catat alasan penolakan
        $topup->update([
            'status' => 'rejected',
            'completed_at' => now(),
            'rejection_reason' => $request->rejection_reason,
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        return redirect()->route('admin.topups.index')->with('success', 'Top up berhasil ditolak.');
    }
}
