<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Topup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TopupController extends Controller
{
    /**
     * Show the form for creating a new topup request.
     */
    public function create()
    {
        return view('topup.create');
    }

    /**
     * Store a newly created topup request in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000', // Minimal 1000
            'payment_method' => 'nullable|string|max:255',
            'proof_of_transfer' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048', // maks 2MB
        ]);

        $topupData = [
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'status' => 'pending',
            'payment_method' => $request->payment_method,
        ];

        // Handle proof of transfer upload
        if ($request->hasFile('proof_of_transfer')) {
            $proofFile = $request->file('proof_of_transfer');
            $fileName = 'proof_' . Auth::id() . '_' . time() . '.' . $proofFile->getClientOriginalExtension();
            $proofFile->storeAs('public/proofs', $fileName);
            $topupData['proof_of_transfer'] = $fileName;
        }

        Topup::create($topupData);

        return redirect()->route('topup.history')->with('success', 'Permintaan top up berhasil dibuat dan menunggu persetujuan admin.');
    }

    /**
     * Display the user's topup history.
     */
    public function history()
    {
        $topups = Topup::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('topup.history', compact('topups'));
    }
}
